<?php
namespace App\Http\Responses;

use App\Http\Contracts\ResponseInterface;

class HtmlResponse implements ResponseInterface
{
    public function __construct(
        private string $html,
        private int $status = 200,
        private array $headers = []
    ) {}

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeaders(): array
    {
        return ['Content-Type' => 'text/html; charset=UTF-8'] + $this->headers;
    }

    public function getBody(): string
    {
        return $this->html;
    }
}

