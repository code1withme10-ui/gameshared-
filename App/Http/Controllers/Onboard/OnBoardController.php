<?php
namespace App\Http\Controllers\Onboard;
use eftec\bladeone\BladeOne;
class  OnBoardController {
    public function index($param=[]) {
      $BladeOne=  new BladeOne(BASE_VIEWS, BASE_CACHE);
        $html= $BladeOne->run('onboard.index',[]);
        return  $html;
        
    }
}