<?php
// App/Http/Controllers/Onboard/IdentityController.php
namespace App\Http\Controllers\Onboard;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
 
class IdentityController extends   BaseController  {
        public function index(ServerRequestInterface $request)
     {
          $token= $request->getAttribute('tenant')  ;  //this must be resolved by TenantResolutionMiddleware 
            return $this->view('onboard.identity', [
            'tenant' => []]);  

    }
}

