<?php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\JsonResponse;
use App\Services\Onboarding\FeaturesService;
use Psr\Http\Message\ServerRequestInterface;

class FeaturesApiController
{
    public function __construct(
        private FeaturesService $featuresService
    ) {}

    public function index(ServerRequestInterface $request): JsonResponse
    {
        $tenant = $request->getAttribute('tenant');
        $features = $this->featuresService->getForTenant($tenant['id']);

        return new JsonResponse([
            'step'     => 'features',
            'tenant'   => $tenant['token'],
            'features' => $features,
        ]);
    }

    public function update(ServerRequestInterface $request): JsonResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data = json_decode((string) $request->getBody(), true);

        $this->featuresService->saveForTenant($tenant['id'], $data);

        return new JsonResponse([
            'status' => 'ok',
            'step'   => 'features',
        ], 201);
    }
}
