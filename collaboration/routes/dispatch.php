<?php
use App\Http\Contracts\ResponseInterface;
use App\Http\Kernel\ResponseEmitter;
use App\Http\Kernel\MiddlewarePipeline;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use App\Http\Middleware\TenantResolutionMiddleware;
 
function dispatch(string $action, 
        array $routeParams = [],
        array $middleware = []): void
{
    try {
        [$class, $method] = explode('@', $action);

        $fqcn = "App\\Http\\Controllers\\{$class}";
        $controller = new $fqcn();

        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $request = $creator->fromGlobals();

        foreach ($routeParams as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $pipeline = new MiddlewarePipeline(
            $middleware
        );

        $response = $pipeline->handle(
            $request,
            fn ($request) => $controller->$method($request, ...array_values($routeParams))
        );

        if (! $response instanceof ResponseInterface) {
            throw new RuntimeException("Invalid response type");
        }

    } catch (\Throwable $e) {
        print_R($e);
        exit;
      //TODO improve  response formate  if  not api  than only  text/error  page
        $response = new \App\Http\Responses\JsonResponse([
            'error' => true,
            'message' => $e->getMessage(),
        ], 500);
    }

    ResponseEmitter::emit($response);
    exit;
}
