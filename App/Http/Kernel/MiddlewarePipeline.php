<?php
namespace App\Http\Kernel;

use App\Http\Contracts\MiddlewareInterface;
use App\Http\Contracts\ResponseInterface;

use Psr\Http\Message\ServerRequestInterface;

class MiddlewarePipeline
{
    public function __construct(
        private array $middlewares
    ) {}

    public function handle(
        ServerRequestInterface $request,
        callable $controller
    ): ResponseInterface {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn ($next, MiddlewareInterface $middleware) =>
                fn ($request) => $middleware->handle($request, $next),
            fn ($request) => $controller($request)
        );

        return $pipeline($request);
    }
}

