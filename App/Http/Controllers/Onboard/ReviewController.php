<?php
// App/Http/Controllers/Onboard/ReviewController.php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\ReviewService;
use App\Repositories\TenantRepository;
use App\Services\Onboarding\IdentityService;
use App\Services\Onboarding\BrandingService;
use App\Services\Onboarding\RulesService;
use App\Services\Onboarding\FeaturesService;
use App\Services\Onboarding\ContentService;
use Psr\Http\Message\ServerRequestInterface;

class ReviewController extends BaseController
{
    private ReviewService $reviewService;

    public function __construct()
    {
        // Manually wire all dependencies – this shows why a factory is useful
        $tenantRepo = new TenantRepository();
        $this->reviewService = new ReviewService(
            $tenantRepo,
            new IdentityService($tenantRepo),
            new BrandingService($tenantRepo),
            new RulesService($tenantRepo),
            new FeaturesService($tenantRepo),
            new ContentService($tenantRepo)
        );
    }

    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $summary = $this->reviewService->getSummary($tenant['id']);

        return $this->view('onboard.review', [
            'tenant'  => $tenant,
            'request' =>$request,
            'summary' => $summary,
        ]);
    }

    public function submit(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $this->reviewService->completeOnboarding($tenant['id']);

        // Redirect to a "success" page or creche dashboard
        return new HtmlResponse('', 302, [
            'Location' => "/onboard/{$tenant['token']}/complete"
        ]);
    }
}
