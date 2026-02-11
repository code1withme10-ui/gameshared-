<?php
 
//require_once '../../vendor/autoload.php';
use Bramus\Router\Router;
use App\Http\Controllers\Onboard\OnBoardController;
use App\Http\Controllers\Onboard\StartController;
use App\Http\Controllers\Onboard\IdentityController;
use App\Http\Kernel\ResponseEmitter;
require_once __DIR__.'/dispatch.php';
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
        routeParams: ['token' => $token]
    );
});

$router->mount('/ ', function () use ($router) {
   
    $router->get('/start', 'StartController@index');

   // $router->get('/*/identity', 'IdentityController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/branding', 'BrandingController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/rules', 'RulesController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/features', 'FeaturesController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/content', 'ContentController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/review', 'ReviewController@index');

});

$router->get('/start/', function ()   {
  
  $obj=  new StartController();  
  
   $response=$obj->index();
    ResponseEmitter::emit($response);
 exit;  
});
$router->run(); 
// ----------------- API ROUTES -----------------
 
