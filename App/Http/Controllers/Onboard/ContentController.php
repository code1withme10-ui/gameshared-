<?php
// App/Http/Controllers/Onboard/ContentController.php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\ContentService;
use App\Repositories\TenantRepository;
use Psr\Http\Message\ServerRequestInterface;

class ContentController extends BaseController
{
    private ContentService $contentService;

    public function __construct()
    {
        $this->contentService = new ContentService(new TenantRepository());
    }

    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $content = $this->contentService->getForTenant($tenant['id']);

        return $this->view('onboard.content', [
            'tenant'  => $tenant,
            'request' =>$request,
            'content' => $content,
        ]);
    }

    public function store(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data = $request->getParsedBody();

        $this->contentService->saveForTenant($tenant['id'], $data);

        // Redirect to review
        return new HtmlResponse('', 302, [
            'Location' => "/onboard/{$tenant['token']}/review"
        ]);
    }
}
