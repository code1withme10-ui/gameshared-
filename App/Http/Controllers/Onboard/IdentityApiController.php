<?php
// App/Http/Controllers/Onboard/IdentityApiController.php
namespace App\Http\Controllers\Onboard;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
 
class IdentityApiController extends   BaseController  {
        public function index(ServerRequestInterface $request)
     {
            $token= $request->getAttribute('tenant')  ;  //this must be resolved by TenantResolutionMiddleware
             
           return new JsonResponse([
            'step'  => 'identity',
            'token' => $token,
            'ip'    => $request->getServerParams()['REMOTE_ADDR'] ?? null,
        ]);
    }
}


