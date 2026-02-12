<?php
// App/Services/Onboarding/ContentService.php

namespace App\Services\Onboarding;

use App\Repositories\TenantRepository;

class ContentService
{
    public function __construct(
        private TenantRepository $tenantRepository
    ) {}

    /**
     * Get static content for a tenant.
     */
    public function getForTenant(int $tenantId): array
    {
        // Default content
        return [
            'about_us'         => 'Welcome to our creche!',
            'admission_guide'  => 'Please read our admission guidelines...',
            'contact_email'    => '',
            'contact_phone'    => '',
            'address'          => '',
        ];
    }

    public function saveForTenant(int $tenantId, array $data): void
    {
        // Save to tenant storage
    }
}
