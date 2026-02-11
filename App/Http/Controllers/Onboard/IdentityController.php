<?php
// App/Http/Controllers/Onboard/IdentityController.php
namespace App\Http\Controllers\Onboard;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
 
class IdentityController extends   BaseController  {
        public function index(ServerRequestInterface $request, string $token)
     {
 
            return $this->view('onboard.identity', [
            'tenant' => []]);  
//           return new JsonResponse([
//            'step'  => 'identity',
//            'token' => $token,
//            'ip'    => $request->getServerParams()['REMOTE_ADDR'] ?? null,
//        ]);
    }
}

