<?php
namespace App\Http\Middleware;

use App\Http\Contracts\MiddlewareInterface;
use App\Http\Contracts\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
 
use App\Repositories\TenantRepository;

class TenantResolutionMiddleware implements MiddlewareInterface
{
    public function handle(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        session_start();

        $tokenFromRoute = $request->getAttribute('token');
        $tokenFromSession = $_SESSION['tenant_token'] ?? null;
        $token = $tokenFromRoute ?? $tokenFromSession;

        if (!$token) {
            throw new \RuntimeException('Tenant token missing');
        }

        $repository = new TenantRepository();
        $tenant = $repository->findByToken($token);

        if (!$tenant) {
            throw new \RuntimeException('Tenant not found');
        }

        // If token came from URI, refresh session
        if ($tokenFromRoute) {
            $_SESSION['tenant_token'] = $token;
            $source = 'uri';
        } else {
            $source = 'session';
        }

        $request = $request
            ->withAttribute('tenant', $tenant)
            ->withAttribute('tenant_source', $source);

        return $next($request);
    }
}


