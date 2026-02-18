<?php

namespace App\Http\Middleware;

use App\Http\Contracts\MiddlewareInterface;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use App\Exceptions\OnboardingException;
use App\Services\AuditService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;

class OnboardingExceptionMiddleware implements MiddlewareInterface
{
    private AuditService $auditService;
    private bool $debugMode;

    public function __construct(AuditService $auditService = null, bool $debugMode = false)
    {
        $this->auditService = $auditService ?? new AuditService();
        $this->debugMode = $debugMode;
    }

    public function process(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        try {
            return $next($request);
        } catch (OnboardingException $e) {
            return $this->handleOnboardingException($e, $request);
        } catch (Exception $e) {
            return $this->handleGenericException($e, $request);
        } catch (Throwable $e) {
            return $this->handleCriticalException($e, $request);
        }
    }

    /**
     * Handle specific onboarding exceptions
     */
    private function handleOnboardingException(OnboardingException $e, ServerRequestInterface $request): ResponseInterface
    {
        // Log the exception for audit trail
        $this->logException($e, $request);

        // Notify admin if required
        if ($e->shouldNotifyAdmin()) {
            $this->notifyAdmin($e, $request);
        }

        // Determine response format based on request
        $wantsJson = $this->wantsJson($request);

        if ($wantsJson) {
            return $this->createJsonErrorResponse($e);
        } else {
            return $this->createHtmlErrorResponse($e);
        }
    }

    /**
     * Handle generic exceptions
     */
    private function handleGenericException(Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        // Log the exception
        $this->logGenericException($e, $request);

        $wantsJson = $this->wantsJson($request);

        if ($wantsJson) {
            return new JsonResponse([
                'error' => 'internal_server_error',
                'message' => 'An unexpected error occurred. Please try again.',
                'code' => 500,
                'timestamp' => date('c')
            ], 500);
        } else {
            return $this->createErrorPage(
                'Something went wrong',
                'An unexpected error occurred. Please try again or contact support if the problem persists.',
                500
            );
        }
    }

    /**
     * Handle critical exceptions (Throwable)
     */
    private function handleCriticalException(Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        // Log critical error
        error_log("CRITICAL ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

        $wantsJson = $this->wantsJson($request);

        if ($wantsJson) {
            return new JsonResponse([
                'error' => 'critical_error',
                'message' => 'A critical error occurred. The system administrator has been notified.',
                'code' => 500,
                'timestamp' => date('c')
            ], 500);
        } else {
            return $this->createErrorPage(
                'System Error',
                'A critical error occurred. The system administrator has been notified.',
                500
            );
        }
    }

    /**
     * Create JSON error response for onboarding exceptions
     */
    private function createJsonErrorResponse(OnboardingException $e): JsonResponse
    {
        $response = [
            'error' => $this->getErrorType($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'timestamp' => date('c')
        ];

        // Add context information in debug mode
        if ($this->debugMode) {
            $response['context'] = $e->getContext();
            $response['severity'] = $e->getSeverity();
            $response['tenant_id'] = $e->getTenantId();
            $response['user_id'] = $e->getUserId();
        }

        // Add specific context based on exception type
        if ($e instanceof \App\Exceptions\StageProgressionException) {
            $response['current_stage'] = $e->getCurrentStage();
            $response['attempted_stage'] = $e->getAttemptedStage();
        }

        if ($e instanceof \App\Exceptions\ConfigurationException) {
            $response['invalid_fields'] = $e->getInvalidFields();
            $response['config_type'] = $e->getConfigType();
        }

        if ($e instanceof \App\Exceptions\TenantValidationException) {
            $response['validation_errors'] = $e->getContext()['validation_errors'] ?? [];
        }

        return new JsonResponse($response, $e->getCode() ?: 400);
    }

    /**
     * Create HTML error response for onboarding exceptions
     */
    private function createHtmlErrorResponse(OnboardingException $e): HtmlResponse
    {
        $title = $this->getErrorTitle($e);
        $message = $e->getMessage();
        $showRetry = $this->shouldShowRetryButton($e);

        // In a real implementation, you would render a proper error template
        $html = $this->renderErrorTemplate($title, $message, $showRetry, $e);

        return new HtmlResponse($html, $e->getCode() ?: 400);
    }

    /**
     * Create generic error page
     */
    private function createErrorPage(string $title, string $message, int $statusCode): HtmlResponse
    {
        $html = $this->renderErrorTemplate($title, $message, false, null);

        return new HtmlResponse($html, $statusCode);
    }

    /**
     * Render error template
     */
    private function renderErrorTemplate(string $title, string $message, bool $showRetry, ?OnboardingException $e): string
    {
        // Simple HTML template - in production, use Blade templates
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f5f5f5; }
        .error-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error-icon { font-size: 48px; color: #e74c3c; margin-bottom: 20px; }
        .error-title { color: #2c3e50; margin-bottom: 15px; }
        .error-message { color: #7f8c8d; line-height: 1.6; margin-bottom: 25px; }
        .retry-btn { background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .retry-btn:hover { background-color: #2980b9; }
        .error-details { margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 4px; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">' . htmlspecialchars($title) . '</h1>
        <p class="error-message">' . htmlspecialchars($message) . '</p>';

        if ($showRetry) {
            $html .= '<button class="retry-btn" onclick="history.back()">Go Back</button>';
        }

        if ($this->debugMode && $e) {
            $html .= '<div class="error-details">
                <strong>Debug Information:</strong><br>
                Exception: ' . get_class($e) . '<br>
                Code: ' . $e->getCode() . '<br>
                Severity: ' . $e->getSeverity() . '<br>
                Tenant ID: ' . htmlspecialchars($e->getTenantId()) . '<br>
                User ID: ' . htmlspecialchars($e->getUserId()) . '<br>
                Context: ' . htmlspecialchars(json_encode($e->getContext())) . '
            </div>';
        }

        $html .= '
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Get error type based on exception class
     */
    private function getErrorType(OnboardingException $e): string
    {
        return match (true) {
            $e instanceof \App\Exceptions\TenantValidationException => 'tenant_validation_error',
            $e instanceof \App\Exceptions\StageProgressionException => 'stage_progression_error',
            $e instanceof \App\Exceptions\ConfigurationException => 'configuration_error',
            $e instanceof \App\Exceptions\FileOperationException => 'file_operation_error',
            $e instanceof \App\Exceptions\TenantIsolationException => 'tenant_isolation_error',
            $e instanceof \App\Exceptions\OnboardingAuthException => 'authentication_error',
            $e instanceof \App\Exceptions\ServiceUnavailableException => 'service_unavailable_error',
            default => 'onboarding_error'
        };
    }

    /**
     * Get user-friendly error title
     */
    private function getErrorTitle(OnboardingException $e): string
    {
        return match (true) {
            $e instanceof \App\Exceptions\TenantValidationException => 'Validation Error',
            $e instanceof \App\Exceptions\StageProgressionException => 'Stage Progression Error',
            $e instanceof \App\Exceptions\ConfigurationException => 'Configuration Error',
            $e instanceof \App\Exceptions\FileOperationException => 'File Operation Error',
            $e instanceof \App\Exceptions\TenantIsolationException => 'Access Denied',
            $e instanceof \App\Exceptions\OnboardingAuthException => 'Authentication Error',
            $e instanceof \App\Exceptions\ServiceUnavailableException => 'Service Unavailable',
            default => 'Onboarding Error'
        };
    }

    /**
     * Determine if retry button should be shown
     */
    private function shouldShowRetryButton(OnboardingException $e): bool
    {
        return match (true) {
            $e instanceof \App\Exceptions\TenantValidationException => true,
            $e instanceof \App\Exceptions\ConfigurationException => true,
            $e instanceof \App\Exceptions\OnboardingAuthException => true,
            $e instanceof \App\Exceptions\ServiceUnavailableException => true,
            default => false
        };
    }

    /**
     * Check if request wants JSON response
     */
    private function wantsJson(ServerRequestInterface $request): bool
    {
        $header = $request->getHeaderLine('Accept');
        return str_contains($header, 'application/json') || 
               str_contains($header, 'application/ld+json');
    }

    /**
     * Log onboarding exception to audit service
     */
    private function logException(OnboardingException $e, ServerRequestInterface $request): void
    {
        try {
            $tenantId = $e->getTenantId() ?: $this->getTenantFromRequest($request);
            $userId = $e->getUserId() ?: $this->getUserFromRequest($request);

            $this->auditService->logAction(
                $tenantId,
                $userId,
                'exception',
                $e->getCode(),
                'exception_thrown',
                null,
                [
                    'exception_type' => get_class($e),
                    'message' => $e->getMessage(),
                    'context' => $e->getContext(),
                    'severity' => $e->getSeverity()
                ]
            );
        } catch (Exception $logException) {
            // Fallback to standard error logging if audit service fails
            error_log("Failed to log exception: " . $logException->getMessage());
        }
    }

    /**
     * Log generic exception
     */
    private function logGenericException(Exception $e, ServerRequestInterface $request): void
    {
        error_log("Generic exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    }

    /**
     * Notify admin of critical errors
     */
    private function notifyAdmin(OnboardingException $e, ServerRequestInterface $request): void
    {
        // In a real implementation, this would send email, Slack notification, etc.
        error_log("ADMIN NOTIFICATION: Critical onboarding error - " . $e->getMessage());
        
        // You could integrate with notification services here:
        // - Email service
        // - Slack/Teams webhook
        // - SMS service
        // - Monitoring system (DataDog, New Relic, etc.)
    }

    /**
     * Extract tenant ID from request
     */
    private function getTenantFromRequest(ServerRequestInterface $request): string
    {
        return $request->getAttribute('tenant') ?? '';
    }

    /**
     * Extract user ID from request
     */
    private function getUserFromRequest(ServerRequestInterface $request): string
    {
        return $request->getAttribute('user') ?? '';
    }
}
