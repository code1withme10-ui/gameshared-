<?php
namespace App\Http\Contracts;

interface ResponseInterface
{
    public function getStatus(): int;
    public function getHeaders(): array;
    public function getBody(): string;
}
  


