<?php

namespace Tests\Unit\Exceptions;

use PHPUnit\Framework\TestCase;
use App\Exceptions\OnboardingException;
use App\Exceptions\TenantValidationException;
use App\Exceptions\StageProgressionException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\FileOperationException;
use App\Exceptions\TenantIsolationException;
use App\Exceptions\OnboardingAuthException;
use App\Exceptions\ServiceUnavailableException;

class OnboardingExceptionTest extends TestCase
{
    public function testBaseOnboardingException(): void
    {
        $tenantId = '01H8X9V4Z2Y3X7W8Q9R0T1U2W';
        $userId = '01H8X9V4Z2Y3X7W8Q9R0T1U2X';
        $context = ['key' => 'value'];
        $severity = 'warning';
        $shouldNotifyAdmin = true;

        $exception = new OnboardingException(
            'Test message',
            400,
            null,
            $tenantId,
            $userId,
            $context,
            $severity,
            $shouldNotifyAdmin
        );

        $this->assertEquals('Test message', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals($tenantId, $exception->getTenantId());
        $this->assertEquals($userId, $exception->getUserId());
        $this->assertEquals($context, $exception->getContext());
        $this->assertEquals($severity, $exception->getSeverity());
        $this->assertEquals($shouldNotifyAdmin, $exception->shouldNotifyAdmin());
    }

    public function testOnboardingExceptionToArray(): void
    {
        $exception = new OnboardingException(
            'Test error',
            422,
            null,
            'tenant-123',
            'user-456',
            ['field' => 'invalid'],
            'error',
            false
        );

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(OnboardingException::class, $array['error']);
        $this->assertEquals('Test error', $array['message']);
        $this->assertEquals(422, $array['code']);
        $this->assertEquals('tenant-123', $array['tenant_id']);
        $this->assertEquals('user-456', $array['user_id']);
        $this->assertEquals(['field' => 'invalid'], $array['context']);
        $this->assertEquals('error', $array['severity']);
        $this->assertFalse($array['should_notify_admin']);
        $this->assertArrayHasKey('timestamp', $array);
    }

    public function testTenantValidationException(): void
    {
        $validationErrors = [
            'name' => 'Name is required',
            'email' => 'Email must be valid'
        ];

        $exception = new TenantValidationException(
            'Validation failed',
            'tenant-123',
            $validationErrors
        );

        $this->assertEquals('Validation failed', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('tenant-123', $exception->getTenantId());
        $this->assertEquals('warning', $exception->getSeverity());
        $this->assertFalse($exception->shouldNotifyAdmin());

        $context = $exception->getContext();
        $this->assertEquals($validationErrors, $context['validation_errors']);
    }

    public function testStageProgressionException(): void
    {
        $currentStage = 'identity';
        $attemptedStage = 'review';

        $exception = new StageProgressionException(
            'Cannot skip stages',
            $currentStage,
            $attemptedStage,
            'tenant-123',
            'user-456'
        );

        $this->assertEquals('Cannot skip stages', $exception->getMessage());
        $this->assertEquals(403, $exception->getCode());
        $this->assertEquals($currentStage, $exception->getCurrentStage());
        $this->assertEquals($attemptedStage, $exception->getAttemptedStage());

        $context = $exception->getContext();
        $this->assertEquals($currentStage, $context['current_stage']);
        $this->assertEquals($attemptedStage, $context['attempted_stage']);
    }

    public function testConfigurationException(): void
    {
        $configType = 'branding';
        $invalidFields = ['primary_color', 'logo_url'];

        $exception = new ConfigurationException(
            'Invalid configuration',
            $configType,
            $invalidFields,
            'tenant-123',
            'user-456'
        );

        $this->assertEquals('Invalid configuration', $exception->getMessage());
        $this->assertEquals(422, $exception->getCode());
        $this->assertEquals($configType, $exception->getConfigType());
        $this->assertEquals($invalidFields, $exception->getInvalidFields());

        $context = $exception->getContext();
        $this->assertEquals($configType, $context['config_type']);
        $this->assertEquals($invalidFields, $context['invalid_fields']);
    }

    public function testFileOperationException(): void
    {
        $operation = 'write';
        $filePath = '/path/to/file.json';

        $exception = new FileOperationException(
            'File write failed',
            $operation,
            $filePath,
            'tenant-123',
            'user-456'
        );

        $this->assertEquals('File write failed', $exception->getMessage());
        $this->assertEquals(500, $exception->getCode());
        $this->assertEquals($operation, $exception->getOperation());
        $this->assertEquals($filePath, $exception->getFilePath());
        $this->assertEquals('error', $exception->getSeverity());
        $this->assertTrue($exception->shouldNotifyAdmin());

        $context = $exception->getContext();
        $this->assertEquals($operation, $context['operation']);
        $this->assertEquals($filePath, $context['file_path']);
    }

    public function testTenantIsolationException(): void
    {
        $violatingTenantId = 'tenant-123';
        $targetTenantId = 'tenant-456';

        $exception = new TenantIsolationException(
            'Cross-tenant access attempted',
            $violatingTenantId,
            $targetTenantId,
            'user-789'
        );

        $this->assertEquals('Cross-tenant access attempted', $exception->getMessage());
        $this->assertEquals(403, $exception->getCode());
        $this->assertEquals($violatingTenantId, $exception->getViolatingTenantId());
        $this->assertEquals($targetTenantId, $exception->getTargetTenantId());
        $this->assertEquals('critical', $exception->getSeverity());
        $this->assertTrue($exception->shouldNotifyAdmin());

        $context = $exception->getContext();
        $this->assertEquals($violatingTenantId, $context['violating_tenant_id']);
        $this->assertEquals($targetTenantId, $context['target_tenant_id']);
    }

    public function testOnboardingAuthException(): void
    {
        $authAction = 'login_attempt';
        $reason = 'Invalid credentials';

        $exception = new OnboardingAuthException(
            'Authentication failed',
            $authAction,
            $reason,
            'tenant-123',
            'user-456'
        );

        $this->assertEquals('Authentication failed', $exception->getMessage());
        $this->assertEquals(401, $exception->getCode());
        $this->assertEquals($authAction, $exception->getAuthAction());
        $this->assertEquals($reason, $exception->getReason());

        $context = $exception->getContext();
        $this->assertEquals($authAction, $context['auth_action']);
        $this->assertEquals($reason, $context['reason']);
    }

    public function testServiceUnavailableException(): void
    {
        $serviceName = 'email_service';
        $retryAfter = 120;

        $exception = new ServiceUnavailableException(
            'Email service is down',
            $serviceName,
            $retryAfter,
            'tenant-123',
            'user-456'
        );

        $this->assertEquals('Email service is down', $exception->getMessage());
        $this->assertEquals(503, $exception->getCode());
        $this->assertEquals($serviceName, $exception->getServiceName());
        $this->assertEquals($retryAfter, $exception->getRetryAfter());

        $context = $exception->getContext();
        $this->assertEquals($serviceName, $context['service_name']);
        $this->assertEquals($retryAfter, $context['retry_after']);
    }

    public function testExceptionInheritance(): void
    {
        $exception = new TenantValidationException('Test', 'tenant-123');

        $this->assertInstanceOf(OnboardingException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);

        $stageException = new StageProgressionException('Test', 'a', 'b');
        $this->assertInstanceOf(OnboardingException::class, $stageException);

        $configException = new ConfigurationException('Test', 'type');
        $this->assertInstanceOf(OnboardingException::class, $configException);

        $fileException = new FileOperationException('Test', 'op', 'path');
        $this->assertInstanceOf(OnboardingException::class, $fileException);

        $isolationException = new TenantIsolationException('Test', 'a', 'b');
        $this->assertInstanceOf(OnboardingException::class, $isolationException);

        $authException = new OnboardingAuthException('Test', 'action');
        $this->assertInstanceOf(OnboardingException::class, $authException);

        $serviceException = new ServiceUnavailableException('Test', 'service');
        $this->assertInstanceOf(OnboardingException::class, $serviceException);
    }

    public function testDefaultValues(): void
    {
        $exception = new OnboardingException('Test message');

        $this->assertEquals('', $exception->getTenantId());
        $this->assertEquals('', $exception->getUserId());
        $this->assertEquals([], $exception->getContext());
        $this->assertEquals('error', $exception->getSeverity());
        $this->assertFalse($exception->shouldNotifyAdmin());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous error');
        $exception = new OnboardingException('Wrapped error', 500, $previous);

        $this->assertEquals($previous, $exception->getPrevious());
        $this->assertEquals('Wrapped error', $exception->getMessage());
    }
}
