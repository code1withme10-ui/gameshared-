<?php
namespace App\Http\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function handle(
        ServerRequestInterface $request,
        callable $next
    ): ResponseInterface;
}

