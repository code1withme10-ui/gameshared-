<?php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use App\Services\AuditService;
use App\Exceptions\OnboardingException;
use App\Exceptions\TenantValidationException;
use App\Exceptions\StageProgressionException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\FileOperationException;
use App\Exceptions\TenantIsolationException;
use App\Exceptions\OnboardingAuthException;
use App\Exceptions\ServiceUnavailableException;
use Psr\Http\Message\ServerRequestInterface;
use eftec\bladeone\BladeOne;
use Exception;

class BaseController {
    protected AuditService $auditService;
    
    public function __construct()
    {
        $this->auditService = new AuditService();
    }
     
    protected function view($tmplt, $param = []) {
        try {
            $BladeOne = new BladeOne(BASE_VIEWS, BASE_CACHE);
            $html = $BladeOne->run($tmplt, $param);
            return new HtmlResponse($html);
        } catch (Exception $e) {
            throw new FileOperationException(
                "Failed to render template: {$tmplt}",
                'template_render',
                $tmplt,
                $this->getTenantIdFromContext(),
                $this->getUserIdFromContext()
            );
        }
    }

    protected function validateTenantAccess(ServerRequestInterface $request): string
    {
        $tenantId = $request->getAttribute('tenant');
        
        if (empty($tenantId)) {
            throw new TenantIsolationException(
                'Tenant not resolved in request',
                'unknown',
                'required',
                $this->getUserIdFromContext()
            );
        }

        return $tenantId;
    }

    protected function validateUserAccess(ServerRequestInterface $request): string
    {
        $userId = $request->getAttribute('user');
        
        if (empty($userId)) {
            throw new OnboardingAuthException(
                'User not authenticated',
                'access_denied',
                'No user session found',
                $this->getTenantIdFromContext()
            );
        }

        return $userId;
    }

    protected function validateInput(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if (is_array($rule)) {
                // Multiple validation rules
                foreach ($rule as $singleRule) {
                    if (!$this->applyValidationRule($data[$field] ?? null, $singleRule)) {
                        $errors[$field][] = "Field {$field} failed validation: {$singleRule}";
                    }
                }
            } else {
                // Single validation rule
                if (!$this->applyValidationRule($data[$field] ?? null, $rule)) {
                    $errors[$field] = "Field {$field} is required or invalid";
                }
            }
        }

        if (!empty($errors)) {
            throw new TenantValidationException(
                'Input validation failed',
                $this->getTenantIdFromContext(),
                $errors
            );
        }

        return $data;
    }

    private function applyValidationRule($value, string $rule): bool
    {
        return match ($rule) {
            'required' => !empty($value),
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
            'ulid' => preg_match('/^[0123456789ABCDEFGHJKMNPQRSTVWXYZ]{26}$/', $value),
            'string' => is_string($value),
            'array' => is_array($value),
            'numeric' => is_numeric($value),
            default => true
        };
    }

    protected function logAction(
        string $tenantId,
        string $userId,
        string $entityType,
        string $entityId,
        string $action,
        ?array $beforeState = null,
        ?array $afterState = null
    ): string {
        try {
            return $this->auditService->logAction(
                $tenantId,
                $userId,
                $entityType,
                $entityId,
                $action,
                $beforeState,
                $afterState
            );
        } catch (Exception $e) {
            // Don't let audit logging failures break the main flow
            error_log("Audit logging failed: " . $e->getMessage());
            return '';
        }
    }

    protected function getTenantIdFromContext(): string
    {
        // This should be overridden by child classes or extracted from request
        return '';
    }

    protected function getUserIdFromContext(): string
    {
        // This should be overridden by child classes or extracted from request
        return '';
    }

    protected function handleServiceException(Exception $e, string $tenantId = '', string $userId = ''): void
    {
        if ($e instanceof OnboardingException) {
            throw $e;
        }

        // Wrap generic exceptions in appropriate onboarding exceptions
        if (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'directory')) {
            throw new FileOperationException(
                "File operation failed: " . $e->getMessage(),
                'unknown',
                'unknown',
                $tenantId,
                $userId
            );
        }

        if (str_contains($e->getMessage(), 'validation') || str_contains($e->getMessage(), 'invalid')) {
            throw new TenantValidationException(
                "Validation failed: " . $e->getMessage(),
                $tenantId
            );
        }

        throw new OnboardingException(
            "Service operation failed: " . $e->getMessage(),
            500,
            $e,
            $tenantId,
            $userId,
            ['original_exception' => get_class($e)],
            'error',
            false
        );
    }
}
