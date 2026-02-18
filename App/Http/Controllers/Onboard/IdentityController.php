<?php
// App/Http/Controllers/Onboard/IdentityController.php
namespace App\Http\Controllers\Onboard;

use App\Http\Responses\JsonResponse;
use App\Http\Responses\HtmlResponse;
use App\Services\Onboarding\IdentityService;
use App\Exceptions\TenantValidationException;
use App\Exceptions\StageProgressionException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\ServiceUnavailableException;
use Psr\Http\Message\ServerRequestInterface;
use Exception;

class IdentityController extends BaseController {
    private IdentityService $identityService;
    
    public function __construct()
    {
        parent::__construct();
        $this->identityService = new IdentityService();
    }

    public function index(ServerRequestInterface $request)
    {
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            // Log page access
            $this->logAction(
                $tenantId,
                $userId,
                'onboarding_page',
                'identity',
                'page_accessed'
            );

            // Get existing identity data if any
            $identityData = $this->identityService->getForTenant($tenantId);
            
            return $this->view('onboard.identity', [
                'request' => $request,
                'tenant' => [
                    'id' => $tenantId,
                    'identity_data' => $identityData
                ]
            ]);

        } catch (Exception $e) {
            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    public function store(ServerRequestInterface $request)
    {
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            // Get and validate input data
            $data = $request->getParsedBody();
            $this->validateInput($data, [
                'creche_name' => 'required|string',
                'registration_number' => 'required|string',
                'principal_name' => 'required|string',
                'contact_email' => ['required', 'email'],
                'contact_phone' => 'required|string',
                'physical_address' => 'required|string',
                'enrollment_capacity' => 'required|numeric'
            ]);

            // Store identity data
            $this->identityService->saveForTenant($tenantId, $data);

            // Log the action
            $this->logAction(
                $tenantId,
                $userId,
                'identity',
                $tenantId,
                'identity_saved',
                null,
                $data
            );

            // Return success response
            return new JsonResponse([
                'success' => true,
                'message' => 'Identity information saved successfully',
                'next_step' => 'features'
            ]);

        } catch (Exception $e) {
            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    public function update(ServerRequestInterface $request)
    {
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            $data = $request->getParsedBody();
            $this->validateInput($data, [
                'creche_name' => 'required|string',
                'registration_number' => 'required|string',
                'principal_name' => 'required|string',
                'contact_email' => ['required', 'email'],
                'contact_phone' => 'required|string',
                'physical_address' => 'required|string',
                'enrollment_capacity' => 'required|numeric'
            ]);

            // Get current data for audit trail
            $currentData = $this->identityService->getForTenant($tenantId);
            
            // Update identity data
            $this->identityService->saveForTenant($tenantId, $data);

            // Log the update
            $this->logAction(
                $tenantId,
                $userId,
                'identity',
                $tenantId,
                'identity_updated',
                $currentData,
                $data
            );

            return new JsonResponse([
                'success' => true,
                'message' => 'Identity information updated successfully'
            ]);

        } catch (Exception $e) {
            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    protected function getTenantIdFromContext(): string
    {
        // This would typically come from the current request or session
        return '';
    }

    protected function getUserIdFromContext(): string
    {
        // This would typically come from the current request or session
        return '';
    }
}

