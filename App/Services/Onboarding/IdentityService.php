<?php

namespace App\Services\Onboarding;

use App\Repositories\TenantRepository;

class IdentityService
{
    public function __construct(
        private TenantRepository $tenantRepository
    ) {}

    public function getForTenant(int $tenantId): array
    {
        return [
            'enrollment_cycles' => [
                ['name' => '2026 Term 1', 'start' => '2026-01-10', 'end' => '2026-06-30'],
            ],
            'age_policy' => 'must_be_2_by_december',
        ];
    }

    public function saveForTenant(int $tenantId, array $data): void
    {
        // Persist data
    }
}
