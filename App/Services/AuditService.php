<?php

namespace App\Services;

use Psr\Http\Message\ServerRequestInterface;
use Exception;

class AuditService
{
    private string $auditLogPath;
    private ServerRequestInterface $request;

    public function __construct(string $auditLogPath = null, ServerRequestInterface $request = null)
    {
        $this->auditLogPath = $auditLogPath ?? STORAGE_PATH . '/data/tenants';
        $this->request = $request;
    }

    /**
     * Log a sensitive action with comprehensive audit trail
     * 
     * @param string $tenantId Tenant ULID
     * @param string $userId User ULID performing the action
     * @param string $entityType Type of entity being acted upon
     * @param string $entityId Entity ULID
     * @param string $action Action being performed
     * @param array|null $beforeState State before action (for hash calculation)
     * @param array|null $afterState State after action (for hash calculation)
     * @return string Event ULID
     * @throws Exception If audit log cannot be written
     */
    public function logAction(
        string $tenantId,
        string $userId,
        string $entityType,
        string $entityId,
        string $action,
        ?array $beforeState = null,
        ?array $afterState = null
    ): string {
        $eventId = $this->generateUlid();
        $timestamp = date('c');
        
        $auditEntry = [
            'event_id' => $eventId,
            'timestamp' => $timestamp,
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'before_hash' => $beforeState ? $this->calculateHash($beforeState) : null,
            'after_hash' => $afterState ? $this->calculateHash($afterState) : null,
            'ip_address' => $this->getClientIp(),
            'user_agent' => $this->getUserAgent()
        ];

        $this->writeAuditLog($tenantId, $auditEntry);
        
        return $eventId;
    }

    /**
     * Log onboarding stage progression
     */
    public function logOnboardingStage(
        string $tenantId,
        string $userId,
        string $fromStage,
        string $toStage,
        ?array $stageData = null
    ): string {
        return $this->logAction(
            $tenantId,
            $userId,
            'onboarding',
            $tenantId,
            "stage_progression: {$fromStage} -> {$toStage}",
            ['current_stage' => $fromStage],
            array_merge(['current_stage' => $toStage], $stageData ?? [])
        );
    }

    /**
     * Log tenant creation
     */
    public function logTenantCreation(string $tenantId, string $userId, array $tenantData): string
    {
        return $this->logAction(
            $tenantId,
            $userId,
            'tenant',
            $tenantId,
            'tenant_created',
            null,
            $tenantData
        );
    }

    /**
     * Log configuration changes
     */
    public function logConfigChange(
        string $tenantId,
        string $userId,
        string $configType,
        string $configId,
        ?array $beforeConfig,
        ?array $afterConfig
    ): string {
        return $this->logAction(
            $tenantId,
            $userId,
            $configType,
            $configId,
            'config_changed',
            $beforeConfig,
            $afterConfig
        );
    }

    /**
     * Log authentication events
     */
    public function logAuthEvent(
        string $tenantId,
        string $userId,
        string $authAction,
        bool $success,
        ?string $failureReason = null
    ): string {
        $context = [
            'success' => $success,
            'failure_reason' => $failureReason
        ];

        return $this->logAction(
            $tenantId,
            $userId,
            'authentication',
            $userId,
            $authAction,
            null,
            $context
        );
    }

    /**
     * Generate ULID for audit events
     */
    private function generateUlid(): string
    {
        // Use the same ULID generation as the rest of the system
        return \Symfony\Component\Uid\Ulid::generate();
    }

    /**
     * Calculate SHA256 hash of data for integrity verification
     */
    private function calculateHash(array $data): string
    {
        return hash('sha256', json_encode($data, JSON_SORT_KEYS));
    }

    /**
     * Get client IP address from request
     */
    private function getClientIp(): string
    {
        if (!$this->request) {
            return 'unknown';
        }

        $serverParams = $this->request->getServerParams();
        
        // Check for forwarded IPs first
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',    // Cloudflare
            'HTTP_X_FORWARDED_FOR',     // Standard proxy
            'HTTP_X_REAL_IP',           // Nginx proxy
            'REMOTE_ADDR'               // Direct connection
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($serverParams[$header])) {
                $ips = explode(',', $serverParams[$header]);
                return trim($ips[0]);
            }
        }

        return 'unknown';
    }

    /**
     * Get user agent from request
     */
    private function getUserAgent(): string
    {
        if (!$this->request) {
            return 'unknown';
        }

        $serverParams = $this->request->getServerParams();
        return $serverParams['HTTP_USER_AGENT'] ?? 'unknown';
    }

    /**
     * Write audit entry to log file with monthly rotation
     */
    private function writeAuditLog(string $tenantId, array $auditEntry): void
    {
        $tenantAuditDir = $this->auditLogPath . '/' . $tenantId . '/audit';
        $logFileName = date('Y-m') . '.log';
        $logFilePath = $tenantAuditDir . '/' . $logFileName;

        // Ensure audit directory exists
        if (!is_dir($tenantAuditDir)) {
            if (!mkdir($tenantAuditDir, 0755, true)) {
                throw new Exception("Failed to create audit directory: {$tenantAuditDir}");
            }
        }

        // Prepare log entry
        $logLine = json_encode($auditEntry) . PHP_EOL;

        // Append to log file (atomic operation)
        if (file_put_contents($logFilePath, $logLine, FILE_APPEND | LOCK_EX) === false) {
            throw new Exception("Failed to write audit log: {$logFilePath}");
        }

        // Ensure file permissions are secure
        chmod($logFilePath, 0640);
    }

    /**
     * Retrieve audit logs for a tenant within a date range
     */
    public function getAuditLogs(
        string $tenantId,
        ?\DateTime $startDate = null,
        ?\DateTime $endDate = null,
        ?string $entityType = null,
        ?string $action = null
    ): array {
        $tenantAuditDir = $this->auditLogPath . '/' . $tenantId . '/audit';
        
        if (!is_dir($tenantAuditDir)) {
            return [];
        }

        $logs = [];
        $start = $startDate ?? new \DateTime('first day of this month');
        $end = $endDate ?? new \DateTime('last day of this month');

        // Iterate through monthly log files
        $current = clone $start;
        while ($current <= $end) {
            $logFile = $tenantAuditDir . '/' . $current->format('Y-m') . '.log';
            
            if (file_exists($logFile)) {
                $fileLogs = $this->parseLogFile($logFile, $entityType, $action);
                $logs = array_merge($logs, $fileLogs);
            }
            
            $current->modify('first day of next month');
        }

        // Sort by timestamp
        usort($logs, function($a, $b) {
            return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
        });

        return $logs;
    }

    /**
     * Parse a single log file and filter results
     */
    private function parseLogFile(string $logFile, ?string $entityType, ?string $action): array
    {
        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $entry = json_decode($line, true);
            
            if ($entry === null) {
                continue; // Skip malformed entries
            }

            // Apply filters
            if ($entityType && $entry['entity_type'] !== $entityType) {
                continue;
            }

            if ($action && !str_contains($entry['action'], $action)) {
                continue;
            }

            $logs[] = $entry;
        }

        return $logs;
    }

    /**
     * Verify audit log integrity using stored hashes
     */
    public function verifyAuditIntegrity(string $tenantId, array $currentEntityState): array
    {
        $issues = [];
        
        // Get recent audit logs for this entity
        $recentLogs = $this->getAuditLogs(
            $tenantId,
            new \DateTime('-24 hours'),
            new \DateTime(),
            $currentEntityState['entity_type'] ?? null
        );

        foreach ($recentLogs as $log) {
            if ($log['after_hash']) {
                // This would require storing historical states or snapshots
                // For now, we'll just verify the hash format
                if (!preg_match('/^[a-f0-9]{64}$/', $log['after_hash'])) {
                    $issues[] = "Invalid hash format in log entry {$log['event_id']}";
                }
            }
        }

        return $issues;
    }
}
