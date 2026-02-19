<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\AuditService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Exception;

class AuditServiceTest extends TestCase
{
    private AuditService $auditService;
    private string $testAuditPath;
    private string $testTenantId;

    protected function setUp(): void
    {
        $this->testAuditPath = sys_get_temp_dir() . '/audit_test_' . uniqid();
        $this->testTenantId = '01H8X9V4Z2Y3X7W8Q9R0T1U2W';
        
        // Create test directory structure
        mkdir($this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit', 0755, true);
        
        $this->auditService = new AuditService($this->testAuditPath . '/tenants');
    }

    protected function tearDown(): void
    {
        // Clean up test files
        $this->removeDirectory($this->testAuditPath);
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    public function testLogActionCreatesAuditEntry(): void
    {
        $userId = '01H8X9V4Z2Y3X7W8Q9R0T1U2X';
        $entityType = 'test_entity';
        $entityId = '01H8X9V4Z2Y3X7W8Q9R0T1U2Y';
        $action = 'test_action';

        $eventId = $this->auditService->logAction(
            $this->testTenantId,
            $userId,
            $entityType,
            $entityId,
            $action
        );

        // Verify event ID is a valid ULID
        $this->assertMatchesRegularExpression('/^[0123456789ABCDEFGHJKMNPQRSTVWXYZ]{26}$/', $eventId);

        // Verify log file was created
        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $this->assertFileExists($logFile);

        // Verify log content
        $logContent = file_get_contents($logFile);
        $logEntry = json_decode(trim($logContent), true);

        $this->assertIsArray($logEntry);
        $this->assertEquals($eventId, $logEntry['event_id']);
        $this->assertEquals($this->testTenantId, $logEntry['tenant_id']);
        $this->assertEquals($userId, $logEntry['user_id']);
        $this->assertEquals($entityType, $logEntry['entity_type']);
        $this->assertEquals($entityId, $logEntry['entity_id']);
        $this->assertEquals($action, $logEntry['action']);
        $this->assertArrayHasKey('timestamp', $logEntry);
        $this->assertArrayHasKey('ip_address', $logEntry);
        $this->assertArrayHasKey('user_agent', $logEntry);
    }

    public function testLogActionWithStateHashes(): void
    {
        $beforeState = ['name' => 'Old Name', 'status' => 'active'];
        $afterState = ['name' => 'New Name', 'status' => 'inactive'];

        $eventId = $this->auditService->logAction(
            $this->testTenantId,
            'test-user',
            'entity',
            'entity-id',
            'state_change',
            $beforeState,
            $afterState
        );

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $logEntry = json_decode(trim(file_get_contents($logFile)), true);

        $this->assertEquals(64, strlen($logEntry['before_hash']));
        $this->assertEquals(64, strlen($logEntry['after_hash']));
        $this->assertNotEquals($logEntry['before_hash'], $logEntry['after_hash']);
    }

    public function testLogOnboardingStage(): void
    {
        $eventId = $this->auditService->logOnboardingStage(
            $this->testTenantId,
            'test-user',
            'identity',
            'branding',
            ['stage_data' => 'test']
        );

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $logEntry = json_decode(trim(file_get_contents($logFile)), true);

        $this->assertEquals('onboarding', $logEntry['entity_type']);
        $this->assertEquals($this->testTenantId, $logEntry['entity_id']);
        $this->assertEquals('stage_progression: identity -> branding', $logEntry['action']);
        $this->assertArrayHasKey('stage_data', $logEntry['after_hash'] ? [] : $logEntry['context']);
    }

    public function testLogTenantCreation(): void
    {
        $tenantData = [
            'name' => 'Test Crèche',
            'registration_number' => 'TEST123',
            'created_at' => date('c')
        ];

        $eventId = $this->auditService->logTenantCreation(
            $this->testTenantId,
            'admin-user',
            $tenantData
        );

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $logEntry = json_decode(trim(file_get_contents($logFile)), true);

        $this->assertEquals('tenant', $logEntry['entity_type']);
        $this->assertEquals($this->testTenantId, $logEntry['entity_id']);
        $this->assertEquals('tenant_created', $logEntry['action']);
        $this->assertNull($logEntry['before_hash']);
        $this->assertNotNull($logEntry['after_hash']);
    }

    public function testLogConfigChange(): void
    {
        $beforeConfig = ['theme' => 'light', 'primary_color' => '#blue'];
        $afterConfig = ['theme' => 'dark', 'primary_color' => '#black'];

        $eventId = $this->auditService->logConfigChange(
            $this->testTenantId,
            'test-user',
            'branding',
            'theme-config',
            $beforeConfig,
            $afterConfig
        );

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $logEntry = json_decode(trim(file_get_contents($logFile)), true);

        $this->assertEquals('branding', $logEntry['entity_type']);
        $this->assertEquals('theme-config', $logEntry['entity_id']);
        $this->assertEquals('config_changed', $logEntry['action']);
        $this->assertNotNull($logEntry['before_hash']);
        $this->assertNotNull($logEntry['after_hash']);
    }

    public function testLogAuthEvent(): void
    {
        $eventId = $this->auditService->logAuthEvent(
            $this->testTenantId,
            'test-user',
            'login_attempt',
            false,
            'Invalid credentials'
        );

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $logEntry = json_decode(trim(file_get_contents($logFile)), true);

        $this->assertEquals('authentication', $logEntry['entity_type']);
        $this->assertEquals('test-user', $logEntry['entity_id']);
        $this->assertEquals('login_attempt', $logEntry['action']);
        
        $context = json_decode($logEntry['after_hash'] ? '{}' : '{}', true) ?: [];
        $this->assertFalse($context['success'] ?? true);
        $this->assertEquals('Invalid credentials', $context['failure_reason'] ?? '');
    }

    public function testGetAuditLogs(): void
    {
        // Create multiple log entries
        $this->auditService->logAction($this->testTenantId, 'user1', 'entity1', 'id1', 'action1');
        $this->auditService->logAction($this->testTenantId, 'user2', 'entity2', 'id2', 'action2');
        $this->auditService->logAction($this->testTenantId, 'user1', 'entity1', 'id3', 'action3');

        $logs = $this->auditService->getAuditLogs($this->testTenantId);

        $this->assertIsArray($logs);
        $this->assertCount(3, $logs);

        // Verify logs are sorted by timestamp
        $timestamps = array_map(fn($log) => $log['timestamp'], $logs);
        $this->assertEquals($timestamps, array_sort($timestamps));
    }

    public function testGetAuditLogsWithFilters(): void
    {
        // Create log entries with different entity types
        $this->auditService->logAction($this->testTenantId, 'user1', 'tenant', 'id1', 'action1');
        $this->auditService->logAction($this->testTenantId, 'user2', 'user', 'id2', 'action2');
        $this->auditService->logAction($this->testTenantId, 'user1', 'tenant', 'id3', 'action3');

        // Filter by entity type
        $tenantLogs = $this->auditService->getAuditLogs($this->testTenantId, null, null, 'tenant');
        $this->assertCount(2, $tenantLogs);
        foreach ($tenantLogs as $log) {
            $this->assertEquals('tenant', $log['entity_type']);
        }

        // Filter by action
        $actionLogs = $this->auditService->getAuditLogs($this->testTenantId, null, null, null, 'action1');
        $this->assertCount(1, $actionLogs);
        $this->assertEquals('action1', $actionLogs[0]['action']);
    }

    public function testGetAuditLogsForNonExistentTenant(): void
    {
        $logs = $this->auditService->getAuditLogs('non-existent-tenant');
        $this->assertIsArray($logs);
        $this->assertEmpty($logs);
    }

    public function testVerifyAuditIntegrity(): void
    {
        $currentState = [
            'entity_type' => 'test_entity',
            'data' => ['name' => 'Test Entity']
        ];

        $issues = $this->auditService->verifyAuditIntegrity($this->testTenantId, $currentState);

        $this->assertIsArray($issues);
        // Should be empty for a clean setup
        $this->assertEmpty($issues);
    }

    public function testLogActionThrowsExceptionOnWriteFailure(): void
    {
        // Create audit service with invalid path to trigger write failure
        $invalidAuditService = new AuditService('/invalid/path/that/does/not/exist');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to create audit directory');

        $invalidAuditService->logAction(
            $this->testTenantId,
            'test-user',
            'entity',
            'id',
            'action'
        );
    }

    public function testHashCalculation(): void
    {
        $data1 = ['name' => 'Test', 'value' => 123];
        $data2 = ['value' => 123, 'name' => 'Test']; // Same data, different order
        $data3 = ['name' => 'Different', 'value' => 123];

        // This is an indirect test since calculateHash is private
        $eventId1 = $this->auditService->logAction($this->testTenantId, 'user', 'entity', 'id1', 'action', null, $data1);
        $eventId2 = $this->auditService->logAction($this->testTenantId, 'user', 'entity', 'id2', 'action', null, $data2);
        $eventId3 = $this->auditService->logAction($this->testTenantId, 'user', 'entity', 'id3', 'action', null, $data3);

        $logFile = $this->testAuditPath . '/tenants/' . $this->testTenantId . '/audit/' . date('Y-m') . '.log';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);

        $log1 = json_decode($lines[0], true);
        $log2 = json_decode($lines[1], true);
        $log3 = json_decode($lines[2], true);

        // Same data should produce same hash regardless of key order
        $this->assertEquals($log1['after_hash'], $log2['after_hash']);
        // Different data should produce different hash
        $this->assertNotEquals($log1['after_hash'], $log3['after_hash']);
    }
}
