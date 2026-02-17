{{-- resources/views/onboard/_wizard.blade.php --}}
 
@php
    $currentPath = $request->getUri()->getPath();
@endphp

<div class="w3-bar w3-light-grey">
    <span class="w3-bar-item {{ preg_match('#/identity$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/identity">1. Identity </a></span>
    <span class="w3-bar-item {{ preg_match('#/branding$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/branding">2. Branding</a></span>
    <span class="w3-bar-item {{ preg_match('#/rules$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/rules">3. Rules</a></span>
    <span class="w3-bar-item {{ preg_match('#/features$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/features">4. Features</a></span>
    <span class="w3-bar-item {{ preg_match('#/content$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/content">5. Content</a></span>
    <span class="w3-bar-item {{ preg_match('#/review$#', $currentPath) ? 'w3-blue' : '' }}"><a href="/onboard/698d27db9194f/review">Review</a></span>
</div>