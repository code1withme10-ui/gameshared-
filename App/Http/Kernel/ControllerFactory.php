<?php
// App/Http/Kernel/ControllerFactory.php

namespace App\Http\Kernel;

use App\Http\Controllers\Onboard\StartController;
use App\Http\Controllers\Onboard\IdentityController;
use App\Http\Controllers\Onboard\BrandingController;
use App\Http\Controllers\Onboard\RulesController;
use App\Http\Controllers\Onboard\FeaturesController;
use App\Http\Controllers\Onboard\ContentController;
use App\Http\Controllers\Onboard\ReviewController;
// API Controllers
use App\Http\Controllers\Onboard\IdentityApiController;
use App\Http\Controllers\Onboard\BrandingApiController;
use App\Http\Controllers\Onboard\RulesApiController;
use App\Http\Controllers\Onboard\FeaturesApiController;
use App\Http\Controllers\Onboard\ContentApiController;
use App\Http\Controllers\Onboard\ReviewApiController;

use App\Repositories\TenantRepository;
use App\Services\Onboarding\IdentityService;
use App\Services\Onboarding\BrandingService;
use App\Services\Onboarding\RulesService;
use App\Services\Onboarding\FeaturesService;
use App\Services\Onboarding\ContentService;
use App\Services\Onboarding\ReviewService;

class ControllerFactory
{
    public static function make(string $class): object
    {
        $tenantRepo = new TenantRepository();

        return match ($class) {
            // Web Controllers
            'Onboard\\StartController'    => new StartController(),
            'Onboard\\IdentityController' => new IdentityController(new IdentityService($tenantRepo)),
            'Onboard\\BrandingController' => new BrandingController(new BrandingService($tenantRepo)),
            'Onboard\\RulesController'    => new RulesController(new RulesService($tenantRepo)),
            'Onboard\\FeaturesController' => new FeaturesController(new FeaturesService($tenantRepo)),
            'Onboard\\ContentController'  => new ContentController(new ContentService($tenantRepo)),
            'Onboard\\ReviewController'   => new ReviewController(
                new ReviewService(
                    $tenantRepo,
                    new IdentityService($tenantRepo),
                    new BrandingService($tenantRepo),
                    new RulesService($tenantRepo),
                    new FeaturesService($tenantRepo),
                    new ContentService($tenantRepo)
                )
            ),

            // API Controllers
            'Onboard\\IdentityApiController' => new IdentityApiController(new IdentityService($tenantRepo)),
            'Onboard\\BrandingApiController' => new BrandingApiController(new BrandingService($tenantRepo)),
            'Onboard\\RulesApiController'    => new RulesApiController(new RulesService($tenantRepo)),
            'Onboard\\FeaturesApiController' => new FeaturesApiController(new FeaturesService($tenantRepo)),
            'Onboard\\ContentApiController'  => new ContentApiController(new ContentService($tenantRepo)),
            'Onboard\\ReviewApiController'   => new ReviewApiController(/* ... */),

            default => new $class(),
        };
    }
}
