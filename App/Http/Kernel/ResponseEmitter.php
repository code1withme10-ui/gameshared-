<?php
namespace App\Http\Kernel;

use App\Http\Contracts\ResponseInterface;

class ResponseEmitter
{
    public static function emit(ResponseInterface $response): void
    {
        http_response_code($response->getStatus());

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . $value, true);
        }

        echo $response->getBody();
    }
}

