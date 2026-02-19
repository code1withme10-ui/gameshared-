<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OnboardingException extends Exception
{
    private string $tenantId;
    private string $userId;
    private array $context;
    private string $severity;
    private bool $shouldNotifyAdmin;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        string $tenantId = '',
        string $userId = '',
        array $context = [],
        string $severity = 'error',
        bool $shouldNotifyAdmin = false
    ) {
        parent::__construct($message, $code, $previous);
        $this->tenantId = $tenantId;
        $this->userId = $userId;
        $this->context = $context;
        $this->severity = $severity;
        $this->shouldNotifyAdmin = $shouldNotifyAdmin;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getSeverity(): string
    {
        return $this->severity;
    }

    public function shouldNotifyAdmin(): bool
    {
        return $this->shouldNotifyAdmin;
    }

    public function toArray(): array
    {
        return [
            'error' => static::class,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'tenant_id' => $this->tenantId,
            'user_id' => $this->userId,
            'context' => $this->context,
            'severity' => $this->severity,
            'should_notify_admin' => $this->shouldNotifyAdmin,
            'timestamp' => date('c'),
        ];
    }
}

/**
 * Tenant validation exceptions
 */
class TenantValidationException extends OnboardingException
{
    public function __construct(
        string $message,
        string $tenantId = '',
        array $validationErrors = []
    ) {
        parent::__construct(
            $message,
            400,
            null,
            $tenantId,
            '',
            ['validation_errors' => $validationErrors],
            'warning',
            false
        );
    }
}

/**
 * Stage progression exceptions
 */
class StageProgressionException extends OnboardingException
{
    private string $currentStage;
    private string $attemptedStage;

    public function __construct(
        string $message,
        string $currentStage,
        string $attemptedStage,
        string $tenantId = '',
        string $userId = ''
    ) {
        $this->currentStage = $currentStage;
        $this->attemptedStage = $attemptedStage;

        parent::__construct(
            $message,
            403,
            null,
            $tenantId,
            $userId,
            [
                'current_stage' => $currentStage,
                'attempted_stage' => $attemptedStage
            ],
            'warning',
            false
        );
    }

    public function getCurrentStage(): string
    {
        return $this->currentStage;
    }

    public function getAttemptedStage(): string
    {
        return $this->attemptedStage;
    }
}

/**
 * Configuration validation exceptions
 */
class ConfigurationException extends OnboardingException
{
    private string $configType;
    private array $invalidFields;

    public function __construct(
        string $message,
        string $configType,
        array $invalidFields = [],
        string $tenantId = '',
        string $userId = ''
    ) {
        $this->configType = $configType;
        $this->invalidFields = $invalidFields;

        parent::__construct(
            $message,
            422,
            null,
            $tenantId,
            $userId,
            [
                'config_type' => $configType,
                'invalid_fields' => $invalidFields
            ],
            'error',
            false
        );
    }

    public function getConfigType(): string
    {
        return $this->configType;
    }

    public function getInvalidFields(): array
    {
        return $this->invalidFields;
    }
}

/**
 * File operation exceptions
 */
class FileOperationException extends OnboardingException
{
    private string $operation;
    private string $filePath;

    public function __construct(
        string $message,
        string $operation,
        string $filePath,
        string $tenantId = '',
        string $userId = ''
    ) {
        $this->operation = $operation;
        $this->filePath = $filePath;

        parent::__construct(
            $message,
            500,
            null,
            $tenantId,
            $userId,
            [
                'operation' => $operation,
                'file_path' => $filePath
            ],
            'error',
            true
        );
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}

/**
 * Tenant isolation exceptions
 */
class TenantIsolationException extends OnboardingException
{
    private string $violatingTenantId;
    private string $targetTenantId;

    public function __construct(
        string $message,
        string $violatingTenantId,
        string $targetTenantId,
        string $userId = ''
    ) {
        $this->violatingTenantId = $violatingTenantId;
        $this->targetTenantId = $targetTenantId;

        parent::__construct(
            $message,
            403,
            null,
            $violatingTenantId,
            $userId,
            [
                'violating_tenant_id' => $violatingTenantId,
                'target_tenant_id' => $targetTenantId
            ],
            'critical',
            true
        );
    }

    public function getViolatingTenantId(): string
    {
        return $this->violatingTenantId;
    }

    public function getTargetTenantId(): string
    {
        return $this->targetTenantId;
    }
}

/**
 * Authentication/Authorization exceptions
 */
class OnboardingAuthException extends OnboardingException
{
    private string $authAction;
    private string $reason;

    public function __construct(
        string $message,
        string $authAction,
        string $reason = '',
        string $tenantId = '',
        string $userId = ''
    ) {
        $this->authAction = $authAction;
        $this->reason = $reason;

        parent::__construct(
            $message,
            401,
            null,
            $tenantId,
            $userId,
            [
                'auth_action' => $authAction,
                'reason' => $reason
            ],
            'warning',
            false
        );
    }

    public function getAuthAction(): string
    {
        return $this->authAction;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}

/**
 * Service unavailable exceptions
 */
class ServiceUnavailableException extends OnboardingException
{
    private string $serviceName;
    private int $retryAfter;

    public function __construct(
        string $message,
        string $serviceName,
        int $retryAfter = 60,
        string $tenantId = '',
        string $userId = ''
    ) {
        $this->serviceName = $serviceName;
        $this->retryAfter = $retryAfter;

        parent::__construct(
            $message,
            503,
            null,
            $tenantId,
            $userId,
            [
                'service_name' => $serviceName,
                'retry_after' => $retryAfter
            ],
            'error',
            false
        );
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
