<?php
// App/Http/Controllers/Onboard/BrandingApiController.php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\JsonResponse;
use App\Services\Onboarding\BrandingService;
use App\Repositories\TenantRepository;
use Psr\Http\Message\ServerRequestInterface;

class BrandingApiController
{
    private BrandingService $brandingService;

    public function __construct()
    {
        $this->brandingService = new BrandingService(new TenantRepository());
    }

    public function index(ServerRequestInterface $request): JsonResponse
    {
        $tenant = $request->getAttribute('tenant');
        $branding = $this->brandingService->getForTenant($tenant['id']);

        return new JsonResponse([
            'step'     => 'branding',
            'tenant'   => $tenant['token'],
            'branding' => $branding,
        ]);
    }

    public function update(ServerRequestInterface $request): JsonResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data = json_decode((string) $request->getBody(), true);

        $this->brandingService->saveForTenant($tenant['id'], $data);

        return new JsonResponse([
            'status' => 'ok',
            'step'   => 'branding',
        ], 201);
    }
}
