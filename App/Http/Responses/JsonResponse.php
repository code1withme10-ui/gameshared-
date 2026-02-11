<?php
namespace App\Http\Responses;

use App\Http\Contracts\ResponseInterface;

class JsonResponse implements ResponseInterface
{
    public function __construct(
        private mixed $data,
        private int $status = 200,
        private array $headers = [],
        private int $flags = JSON_UNESCAPED_UNICODE
    ) {}

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeaders(): array
    {
        return ['Content-Type' => 'application/json'] + $this->headers;
    }

    public function getBody(): string
    {
        return json_encode($this->data, $this->flags);
    }
}

