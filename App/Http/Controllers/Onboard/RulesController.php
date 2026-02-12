<?php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\RulesService;
use App\Repositories\TenantRepository;
use Psr\Http\Message\ServerRequestInterface;

class RulesController extends BaseController
{
    private RulesService $rulesService;
    public function __construct(
        
    ) {
      $this->rulesService = new  RulesService(new TenantRepository()) ;
    }

    public function index(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $rules  = $this->rulesService->getForTenant($tenant['id']);

        return $this->view('onboard.rules', [
            'tenant' => $tenant,
            'rules'  => $rules,
        ]);
    }

    public function store(ServerRequestInterface $request): HtmlResponse
    {
        $tenant = $request->getAttribute('tenant');
        $data   = $request->getParsedBody();

        $this->rulesService->saveForTenant($tenant['id'], $data);

        return new HtmlResponse('', 302, [
            'Location' => "/onboard/{$tenant['token']}/features"
        ]);
    }
}
