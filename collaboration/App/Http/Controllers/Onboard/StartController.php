<?php
namespace App\Http\Controllers\Onboard;
require_once __DIR__.'/BaseController.php';
class StartController extends BaseController  
{
    public function index()
    {
         
        return $this->view('onboard.start',[]);
    }
}

