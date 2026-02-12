{{-- resources/views/onboard/_wizard.blade.php --}}
 
@php
    $currentPath = $request->getUri()->getPath();
@endphp

<div class="w3-bar w3-light-grey">
    <span class="w3-bar-item {{ preg_match('#/identity$#', $currentPath) ? 'w3-blue' : '' }}">1. Identity</span>
    <span class="w3-bar-item {{ preg_match('#/branding$#', $currentPath) ? 'w3-blue' : '' }}">2. Branding</span>
    <span class="w3-bar-item {{ preg_match('#/rules$#', $currentPath) ? 'w3-blue' : '' }}">3. Rules</span>
    <span class="w3-bar-item {{ preg_match('#/features$#', $currentPath) ? 'w3-blue' : '' }}">4. Features</span>
    <span class="w3-bar-item {{ preg_match('#/content$#', $currentPath) ? 'w3-blue' : '' }}">5. Content</span>
    <span class="w3-bar-item {{ preg_match('#/review$#', $currentPath) ? 'w3-blue' : '' }}">Review</span>
</div>