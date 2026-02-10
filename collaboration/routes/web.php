<?php
 
//require_once '../../vendor/autoload.php';
use Bramus\Router\Router;
use App\Http\Controllers\Onboard\OnBoardController;
use App\Http\Controllers\Onboard\StartController;
use App\Http\Controllers\Onboard\IdentityController;

// Middleware
 

// Controllers
require_once __DIR__.'/../App/Http/Controllers/Onboard/OnBoardController.php';
require_once __DIR__.'/../App/Http/Controllers/Onboard/StartController.php';
use App\Http\Controllers\Business\BusinessController;
 
 

// Router
$router = new Router();


$router->mount('/onboard', function () use ($router) {
  echo "xxxxxxxsssssxxxxxxxxxx";
    $router->get('/start', 'StartController@index');
$router->get('/*', function ()   {
 echo "Starting ....";
  $obj=  new IdentityController();  
  
  echo $obj->index();
 exit;  
});
   // $router->get('/*/identity', 'IdentityController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/branding', 'BrandingController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/rules', 'RulesController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/features', 'FeaturesController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/content', 'ContentController@index');
    $router->get('/(\w+-\w+-\w+-\w+-\w+)/review', 'ReviewController@index');

});

$router->get('/start/', function ()   {
 echo "Starting ....";
  $obj=  new StartController();  
  
  echo $obj->index();
 exit;  
});
$router->run(); 
// ----------------- API ROUTES -----------------

// GET /api/businesses
$router->get('/api/businesses', function ()   {

    $request = (object)[
        // Arbitrary request context
        'params' => $_GET,

        // Controller is attached, not invoked here
        'controller' => fn () =>
            (new BusinessController())->index()
    ];

   
    
   
})
;
