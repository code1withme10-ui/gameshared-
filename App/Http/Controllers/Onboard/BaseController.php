<?php

namespace App\Http\Controllers\Onboard;
use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
  
use eftec\bladeone\BladeOne;
class  BaseController {
     
    protected function view ($tmplt,$param) {
      $BladeOne=  new BladeOne(BASE_VIEWS, BASE_CACHE);
       $html= $BladeOne->run($tmplt,$param);
        

        return new HtmlResponse($html);
         
    }
}
