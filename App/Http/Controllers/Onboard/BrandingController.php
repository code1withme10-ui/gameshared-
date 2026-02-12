<?php
// App/Http/Controllers/Onboard/BrandingController.php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\BrandingService;
use App\Repositories\TenantRepository;
use Psr\Http\Message\ServerRequestInterface;

class BrandingController extends BaseController
{
    private BrandingService $brandingService;

    public function __construct()
    {
        // Temporary direct instantiation – will be replaced by ControllerFactory
        $this->brandingService = new BrandingService(new TenantRepository());
    }

    /**
     * Display the branding form.
     */
    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $branding = $this->brandingService->getForTenant($tenant['id']);

        return $this->view('onboard.branding', [
            'tenant'   => $tenant,
            'request' =>$request,
            'branding' => $branding,
        ]);
    }

    /**
     * Handle form submission.
     */
    public function store(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data   = $request->getParsedBody();

        // TODO: Handle logo file upload if present
        $this->brandingService->saveForTenant($tenant['id'], $data);

        // Redirect to next step (content)
        return new HtmlResponse('', 302, [
            'Location' => "/onboard/{$tenant['token']}/content"
        ]);
    }
}
