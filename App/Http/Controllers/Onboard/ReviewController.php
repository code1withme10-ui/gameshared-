<?php
// App/Http/Controllers/Onboard/ReviewController.php

namespace App\Http\Controllers\Onboard;

use App\Http\Responses\HtmlResponse;
use App\Http\Responses\JsonResponse;
use App\Services\Onboarding\ReviewService;
use App\Repositories\TenantRepository;
use App\Services\Onboarding\IdentityService;
use App\Services\Onboarding\BrandingService;
use App\Services\Onboarding\RulesService;
use App\Services\Onboarding\FeaturesService;
use App\Services\Onboarding\ContentService;
use App\Exceptions\TenantValidationException;
use App\Exceptions\StageProgressionException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\ServiceUnavailableException;
use App\Exceptions\OnboardingAuthException;
use Psr\Http\Message\ServerRequestInterface;
use Exception;

class ReviewController extends BaseController
{
    private ReviewService $reviewService;

    public function __construct()
    {
        parent::__construct();
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
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            // Log review page access
            $this->logAction(
                $tenantId,
                $userId,
                'onboarding_page',
                'review',
                'review_page_accessed'
            );

            // Get comprehensive summary
            $summary = $this->reviewService->getSummary($tenantId);

            // Validate that all required stages are complete
            $this->validateOnboardingCompletion($summary);

            return $this->view('onboard.review', [
                'tenant'  => [
                    'id' => $tenantId,
                    'token' => $request->getAttribute('token', '')
                ],
                'request' => $request,
                'summary' => $summary,
                'validation_status' => $this->getValidationStatus($summary)
            ]);

        } catch (Exception $e) {
            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    public function submit(ServerRequestInterface $request): HtmlResponse
    {
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            // Get current state for audit trail
            $currentSummary = $this->reviewService->getSummary($tenantId);
            
            // Validate final submission requirements
            $this->validateFinalSubmission($currentSummary);

            // Log submission attempt
            $this->logAction(
                $tenantId,
                $userId,
                'onboarding',
                $tenantId,
                'final_submission_attempted',
                $currentSummary
            );

            // Complete onboarding process
            $result = $this->reviewService->completeOnboarding($tenantId);

            // Log successful completion
            $this->logAction(
                $tenantId,
                $userId,
                'onboarding',
                $tenantId,
                'onboarding_completed',
                $currentSummary,
                ['completed_at' => date('c'), 'result' => $result]
            );

            // Redirect to success page
            return new HtmlResponse('', 302, [
                'Location' => "/onboard/{$request->getAttribute('token')}/complete"
            ]);

        } catch (Exception $e) {
            // Log failed submission
            $this->logAction(
                $this->getTenantIdFromContext(),
                $this->getUserIdFromContext(),
                'onboarding',
                $this->getTenantIdFromContext(),
                'final_submission_failed',
                null,
                ['error' => $e->getMessage()]
            );

            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    public function validate(ServerRequestInterface $request): JsonResponse
    {
        try {
            $tenantId = $this->validateTenantAccess($request);
            $userId = $this->validateUserAccess($request);
            
            $summary = $this->reviewService->getSummary($tenantId);
            $validationResults = $this->performDetailedValidation($summary);

            // Log validation results
            $this->logAction(
                $tenantId,
                $userId,
                'onboarding_validation',
                $tenantId,
                'validation_performed',
                null,
                $validationResults
            );

            return new JsonResponse([
                'success' => true,
                'validation_results' => $validationResults,
                'can_submit' => $validationResults['overall_status'] === 'valid',
                'errors' => $validationResults['errors'] ?? []
            ]);

        } catch (Exception $e) {
            $this->handleServiceException($e, $this->getTenantIdFromContext());
            throw $e;
        }
    }

    private function validateOnboardingCompletion(array $summary): void
    {
        $requiredStages = ['identity', 'features', 'rules', 'branding', 'content'];
        $missingStages = [];

        foreach ($requiredStages as $stage) {
            if (empty($summary[$stage]) || !$summary[$stage]['completed']) {
                $missingStages[] = $stage;
            }
        }

        if (!empty($missingStages)) {
            throw new StageProgressionException(
                'Cannot access review page - incomplete onboarding stages',
                'incomplete',
                'review',
                $this->getTenantIdFromContext(),
                $this->getUserIdFromContext()
            );
        }
    }

    private function validateFinalSubmission(array $summary): void
    {
        // Check if all validations pass
        $validationResults = $this->performDetailedValidation($summary);
        
        if ($validationResults['overall_status'] !== 'valid') {
            throw new TenantValidationException(
                'Cannot submit - validation errors exist',
                $this->getTenantIdFromContext(),
                $validationResults['errors'] ?? []
            );
        }

        // Additional business logic validations
        if (empty($summary['identity']['creche_name'])) {
            throw new ConfigurationException(
                'Crèche name is required for submission',
                'identity',
                ['creche_name'],
                $this->getTenantIdFromContext(),
                $this->getUserIdFromContext()
            );
        }

        if (empty($summary['rules']['admission_rules'])) {
            throw new ConfigurationException(
                'Admission rules must be configured before submission',
                'rules',
                ['admission_rules'],
                $this->getTenantIdFromContext(),
                $this->getUserIdFromContext()
            );
        }
    }

    private function performDetailedValidation(array $summary): array
    {
        $errors = [];
        $warnings = [];
        $status = 'valid';

        // Validate identity information
        if (empty($summary['identity']['creche_name'])) {
            $errors['identity'][] = 'Crèche name is required';
            $status = 'invalid';
        }

        if (empty($summary['identity']['contact_email'])) {
            $errors['identity'][] = 'Contact email is required';
            $status = 'invalid';
        }

        // Validate rules configuration
        if (empty($summary['rules']['admission_rules'])) {
            $errors['rules'][] = 'Admission rules must be configured';
            $status = 'invalid';
        }

        // Validate branding
        if (empty($summary['branding']['theme'])) {
            $warnings['branding'][] = 'No theme configured - default theme will be used';
        }

        // Validate content
        if (empty($summary['content']['pages'])) {
            $errors['content'][] = 'Content pages must be created';
            $status = 'invalid';
        }

        return [
            'overall_status' => $status,
            'errors' => $errors,
            'warnings' => $warnings,
            'stage_status' => [
                'identity' => !empty($summary['identity']) ? 'complete' : 'incomplete',
                'features' => !empty($summary['features']) ? 'complete' : 'incomplete',
                'rules' => !empty($summary['rules']) ? 'complete' : 'incomplete',
                'branding' => !empty($summary['branding']) ? 'complete' : 'incomplete',
                'content' => !empty($summary['content']) ? 'complete' : 'incomplete'
            ]
        ];
    }

    private function getValidationStatus(array $summary): array
    {
        $validationResults = $this->performDetailedValidation($summary);
        
        return [
            'can_submit' => $validationResults['overall_status'] === 'valid',
            'error_count' => count($validationResults['errors'], COUNT_RECURSIVE),
            'warning_count' => count($validationResults['warnings'], COUNT_RECURSIVE),
            'stage_completion' => $validationResults['stage_status']
        ];
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
