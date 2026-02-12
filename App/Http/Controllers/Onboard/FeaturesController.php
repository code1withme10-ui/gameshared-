<?php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\FeaturesService;
use App\Repositories\TenantRepository;
use Psr\Http\Message\ServerRequestInterface;

class FeaturesController extends BaseController
{
    private FeaturesService $featuresService;
    public function __construct(
        
    ) {
        $this->featuresService= new  FeaturesService ( new TenantRepository());
    }

    /**
     * Display the features configuration form.
     */
    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
         
        // In real usage, load existing config from the service
        $features = $this->featuresService->getForTenant($tenant['id']);

        return $this->view('onboard.features', [
            'tenant'   => $tenant,
            'features' => $features,
        ]);
    }

    /**
     * Handle form submission (POST).
     * Note: The route for POST must be defined separately.
     */
    public function store(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data   = $request->getParsedBody();

        // Delegate to service
        $this->featuresService->saveForTenant($tenant['id'], $data);
 
        // Redirect to next step (e.g., /content)
        return new HtmlResponse('', 302, [
            'Location' => "/onboard/{$tenant['token']}/content"
        ]);
    }
}
