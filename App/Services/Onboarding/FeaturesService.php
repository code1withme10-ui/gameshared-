<?php

namespace App\Services\Onboarding;

use App\Repositories\TenantRepository; // your existing repo

class FeaturesService
{
    public function __construct(
        private TenantRepository $tenantRepository
    ) {}

    /**
     * Get features configuration for a tenant.
     */
    public function getForTenant(int $tenantId): array
    {
        // TODO: Load from tenant's JSON file or DB
        // For now, return defaults
        return [
            'waitlist_enabled' => false,
            'document_uploads' => true,
            'max_children_per_parent' => 3,
        ];
    }

    /**
     * Save features configuration.
     */
    public function saveForTenant(int $tenantId, array $data): void
    {
        // TODO: Write to tenant storage
        // Example: $this->tenantRepository->saveConfig($tenantId, 'features', $data);
    }
}