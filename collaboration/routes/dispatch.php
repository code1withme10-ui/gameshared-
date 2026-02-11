<?php
use App\Http\Contracts\ResponseInterface;
use App\Http\Kernel\ResponseEmitter;
use App\Http\Kernel\MiddlewarePipeline;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

 
function dispatch(string $action, array $routeParams = []): void
{
    [$class, $method] = explode('@', $action);

    $fqcn = "App\\Http\\Controllers\\{$class}";
    $controller = new $fqcn();
// Create PSR-7 request properly
   
     $psr17Factory = new Psr17Factory();
    $creator = new ServerRequestCreator(
        $psr17Factory,
        $psr17Factory,
        $psr17Factory,
        $psr17Factory
    );

    $request = $creator->fromGlobals();

    // Attach route parameters
    foreach ($routeParams as $key => $value) {
        $request = $request->withAttribute($key, $value);
    }

    $pipeline = new MiddlewarePipeline([
        // new AuthMiddleware(),
        // new CsrfMiddleware(),
    ]);

    $response = $pipeline->handle(
        $request,
        fn ($request) => $controller->$method($request, ...array_values($routeParams))
    );

    if (! $response instanceof ResponseInterface) {
        throw new RuntimeException(
            "{$fqcn}::{$method} must return ResponseInterface"
        );
    }

    ResponseEmitter::emit($response);
    exit;
}
