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
$router->get('/test', function () {
    echo 'OK';
    exit;
});

$router->get('/([a-z0-9]+)/identity', function ($token) {
    dispatch(
            action: 'Onboard\\IdentityController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});
    $router->get('/([a-z0-9]+)/features', 
                       function ($token) {
    dispatch(
            action: 'Onboard\\FeaturesController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});
$router->post('/([a-z0-9]+)/features', 
                       function ($token) {
    dispatch(
            action: 'Onboard\\FeaturesController@store',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});


    $router->get('/([a-z0-9]+)/rules',
            function ($token) {
    dispatch(
            action: 'Onboard\\RulesController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
} );

$router->mount('/', function () use ($router) {

    $router->get('/start', 'StartController@index');

    // $router->get('/*/identity', 'IdentityController@index');
    $router->get('/([a-z0-9]+)/branding',
            function ($token) {
    dispatch(
            action: 'Onboard\\BrandingController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});
    $router->get('/([a-z0-9]+)content',                       function ($token) {
    dispatch(
            action: 'Onboard\\ContentController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
});
    $router->get('/([a-z0-9]+)/review',
                                 function ($token) {
    dispatch(
            action: 'Onboard\\ReviewController@index',
            routeParams: ['token' => $token],
            middleware: [
                new TenantResolutionMiddleware()
            ]
    );
}
            );
});

$router->get('/start/', function () {

    $obj = new StartController();

    $response = $obj->index();
    ResponseEmitter::emit($response);
    exit;
});
$router->get('/', function () {

    $obj = new StartController();

    $response = $obj->index();
    ResponseEmitter::emit($response);
    exit;
});

$router->run();
// ----------------- API ROUTES -----------------
 
