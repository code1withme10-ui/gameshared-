<?php
// App/Http/Controllers/Onboard/IdentityController.php
namespace App\Http\Controllers\Onboard;

class IdentityController {
    public function index() {
        echo "xxxxxxxxxxxxxttttttttttttt";
        return this->view('onboard.identity', [
            'tenant' => []
        ]);
    }
}

