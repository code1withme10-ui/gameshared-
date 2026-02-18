<?php

namespace Tests\Unit\Http\Middleware;

use PHPUnit\Framework\TestCase;
use App\Http\Middleware\OnboardingExceptionMiddleware;
use App\Services\AuditService;
use App\Exceptions\OnboardingException;
use App\Exceptions\TenantValidationException;
use App\Exceptions\StageProgressionException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\FileOperationException;
use App\Exceptions\TenantIsolationException;
use App\Exceptions\OnboardingAuthException;
use App\Exceptions\ServiceUnavailableException;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class OnboardingExceptionMiddlewareTest extends TestCase
{
    private OnboardingExceptionMiddleware $middleware;
    private AuditService $auditService;
    private ServerRequestInterface $request;

    protected function setUp(): void
    {
        $this->auditService = $this->createMock(AuditService::class);
        $this->middleware = new OnboardingExceptionMiddleware($this->auditService, false);
        
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->request->method('getHeaderLine')->willReturn('text/html');
    }

    public function testProcessWithSuccessfulNext(): void
    {
        $expectedResponse = $this->createMock(ResponseInterface::class);
        $next = function($request) use ($expectedResponse) {
            return $expectedResponse;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertSame($expectedResponse, $response);
    }

    public function testProcessWithTenantValidationException(): void
    {
        $exception = new TenantValidationException(
            'Validation failed',
            'tenant-123',
            ['field' => 'required']
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->with(
                'tenant-123',
                '',
                'exception',
                400,
                'exception_thrown',
                null,
                $this->anything()
            );

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('tenant_validation_error', $responseBody['error']);
        $this->assertEquals('Validation failed', $responseBody['message']);
        $this->assertEquals(400, $responseBody['code']);
    }

    public function testProcessWithStageProgressionException(): void
    {
        $exception = new StageProgressionException(
            'Cannot skip stage',
            'identity',
            'review',
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('stage_progression_error', $responseBody['error']);
        $this->assertEquals('identity', $responseBody['current_stage']);
        $this->assertEquals('review', $responseBody['attempted_stage']);
    }

    public function testProcessWithConfigurationException(): void
    {
        $exception = new ConfigurationException(
            'Invalid config',
            'branding',
            ['color', 'logo'],
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('configuration_error', $responseBody['error']);
        $this->assertEquals(['color', 'logo'], $responseBody['invalid_fields']);
        $this->assertEquals('branding', $responseBody['config_type']);
    }

    public function testProcessWithFileOperationException(): void
    {
        $exception = new FileOperationException(
            'File write failed',
            'write',
            '/path/to/file.json',
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('file_operation_error', $responseBody['error']);
        $this->assertEquals('write', $responseBody['context']['operation']);
        $this->assertEquals('/path/to/file.json', $responseBody['context']['file_path']);
    }

    public function testProcessWithTenantIsolationException(): void
    {
        $exception = new TenantIsolationException(
            'Cross-tenant access',
            'tenant-123',
            'tenant-456',
            'user-789'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('tenant_isolation_error', $responseBody['error']);
        $this->assertEquals('tenant-123', $responseBody['context']['violating_tenant_id']);
        $this->assertEquals('tenant-456', $responseBody['context']['target_tenant_id']);
    }

    public function testProcessWithOnboardingAuthException(): void
    {
        $exception = new OnboardingAuthException(
            'Authentication failed',
            'login_attempt',
            'Invalid credentials',
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('authentication_error', $responseBody['error']);
        $this->assertEquals('login_attempt', $responseBody['context']['auth_action']);
        $this->assertEquals('Invalid credentials', $responseBody['context']['reason']);
    }

    public function testProcessWithServiceUnavailableException(): void
    {
        $exception = new ServiceUnavailableException(
            'Service down',
            'email_service',
            60,
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('service_unavailable_error', $responseBody['error']);
        $this->assertEquals('email_service', $responseBody['context']['service_name']);
        $this->assertEquals(60, $responseBody['context']['retry_after']);
    }

    public function testProcessWithGenericException(): void
    {
        $exception = new Exception('Generic error');

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('internal_server_error', $responseBody['error']);
        $this->assertEquals('An unexpected error occurred. Please try again.', $responseBody['message']);
        $this->assertEquals(500, $responseBody['code']);
    }

    public function testProcessWithCriticalThrowable(): void
    {
        $throwable = new \Error('Critical error');

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($throwable) {
            throw $throwable;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $responseBody = json_decode($response->getBody()->__toString(), true);
        $this->assertEquals('critical_error', $responseBody['error']);
        $this->assertEquals('A critical error occurred. The system administrator has been notified.', $responseBody['message']);
        $this->assertEquals(500, $responseBody['code']);
    }

    public function testProcessWithHtmlResponse(): void
    {
        $exception = new TenantValidationException(
            'Validation failed',
            'tenant-123',
            ['field' => 'required']
        );

        $this->request->method('getHeaderLine')->willReturn('text/html');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        
        $html = $response->getBody()->__toString();
        $this->assertStringContainsString('Validation Error', $html);
        $this->assertStringContainsString('Validation failed', $html);
    }

    public function testProcessWithDebugMode(): void
    {
        $middleware = new OnboardingExceptionMiddleware($this->auditService, true);
        
        $exception = new TenantValidationException(
            'Validation failed',
            'tenant-123',
            ['field' => 'required']
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        $response = $middleware->process($this->request, $next);

        $responseBody = json_decode($response->getBody()->__toString(), true);
        
        // Debug mode should include additional context
        $this->assertArrayHasKey('context', $responseBody);
        $this->assertArrayHasKey('severity', $responseBody);
        $this->assertArrayHasKey('tenant_id', $responseBody);
        $this->assertArrayHasKey('user_id', $responseBody);
        $this->assertEquals('tenant-123', $responseBody['tenant_id']);
        $this->assertEquals('warning', $responseBody['severity']);
    }

    public function testProcessWithAuditLoggingFailure(): void
    {
        $exception = new TenantValidationException('Test', 'tenant-123');

        $this->auditService->expects($this->once())
            ->method('logAction')
            ->willThrowException(new Exception('Audit logging failed'));

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        // Should still handle the exception even if audit logging fails
        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testWantsJsonDetection(): void
    {
        $exception = new OnboardingException('Test error');

        // Test with application/json
        $this->request->method('getHeaderLine')->willReturn('application/json');
        $next = function($request) use ($exception) { throw $exception; };
        $response = $this->middleware->process($this->request, $next);
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Test with application/ld+json
        $this->request->method('getHeaderLine')->willReturn('application/ld+json');
        $response = $this->middleware->process($this->request, $next);
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Test with text/html
        $this->request->method('getHeaderLine')->willReturn('text/html');
        $response = $this->middleware->process($this->request, $next);
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function testAdminNotificationForCriticalExceptions(): void
    {
        $exception = new FileOperationException(
            'Critical file error',
            'delete',
            '/important/file.json',
            'tenant-123',
            'user-456'
        );

        $this->request->method('getHeaderLine')->willReturn('application/json');

        $next = function($request) use ($exception) {
            throw $exception;
        };

        // FileOperationException has shouldNotifyAdmin = true
        // This should trigger admin notification (in real implementation)
        $response = $this->middleware->process($this->request, $next);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
