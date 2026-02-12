<?php
// App/Services/Onboarding/BrandingService.php

namespace App\Services\Onboarding;

use App\Repositories\TenantRepository;

class BrandingService
{
    public function __construct(
        private TenantRepository $tenantRepository
    ) {}

    /**
     * Get branding configuration for a tenant.
     */
    public function getForTenant(int $tenantId): array
    {
        // TODO: Load from tenant storage (JSON/DB)
        // Defaults for new crèches
        return [
            'primary_color'   => '#2196F3',
            'secondary_color' => '#FF9800',
            'logo_url'        => null,
        ];
    }

    /**
     * Save branding configuration.
     */
    public function saveForTenant(int $tenantId, array $data): void
    {
        // TODO: Write to tenant storage
        // $this->tenantRepository->saveConfig($tenantId, 'branding', $data);
    }
}
