<?php

namespace App\Http\Controllers\Onboard;
 
use eftec\bladeone\BladeOne;
class  BaseController {
     
    protected function view ($tmplt,$param) {
      $BladeOne=  new BladeOne(BASE_VIEWS, BASE_CACHE);
        return  $BladeOne->run($tmplt,$param);
         
    }
}
