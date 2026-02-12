<?php

//require_once '../../vendor/autoload.php';
use Bramus\Router\Router;
use App\Http\Controllers\Onboard\OnBoardController;
use App\Http\Controllers\Onboard\StartController;
use App\Http\Controllers\Onboard\IdentityController;
use App\Http\Middleware\TenantResolutionMiddleware;
use App\Http\Kernel\ResponseEmitter;

require_once __DIR__ . '/dispatch.php';

// Middleware
// Controllers

use App\Http\Controllers\Business\BusinessController;

// Router
$router = new Router();

$router->get('/api/([a-z0-9]+)/identity', function ($token) {
    dispatch(
            action: 'Onboard\\IdentityApiController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});
 
$router->run();
// ----------------- API ROUTES -----------------
 
