<?php
// App/Services/Onboarding/ReviewService.php

namespace App\Services\Onboarding;

use App\Repositories\TenantRepository;

class ReviewService
{
    public function __construct(
        private TenantRepository $tenantRepository,
        private IdentityService  $identityService,
        private BrandingService  $brandingService,
        private RulesService     $rulesService,
        private FeaturesService  $featuresService,
        private ContentService   $contentService
    ) {}

    public function getSummary(int $tenantId): array
    {
        return [
            'identity' => $this->identityService->getForTenant($tenantId),
            'branding' => $this->brandingService->getForTenant($tenantId),
            'rules'    => $this->rulesService->getForTenant($tenantId),
            'features' => $this->featuresService->getForTenant($tenantId),
            'content'  => $this->contentService->getForTenant($tenantId),
        ];
    }

    public function completeOnboarding(int $tenantId): void
    {
        // Mark tenant as active, send notifications, etc.
        // $this->tenantRepository->updateStatus($tenantId, 'active');
    }
}
