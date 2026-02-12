@extends('layouts.admin.app')

@section('title', 'Feature Settings')
@section('content')
@include('onboard._wizard')
<div class="w3-card w3-white w3-padding">
    <h3>Review & Submit</h3>
    <p>Please review the information below before completing onboarding.</p>

    <div class="w3-container w3-margin-top">
        <h4>Identity</h4>
        <p><strong>Legal Name:</strong> {{ $summary['identity']['legal_name'] ?? 'Not set' }}</p>
        <p><strong>Display Name:</strong> {{ $summary['identity']['display_name'] ?? 'Not set' }}</p>
        <p><strong>Registration:</strong> {{ $summary['identity']['registration_type'] ?? '' }} {{ $summary['identity']['registration_number'] ?? '' }}</p>
        <p><strong>Admin Email:</strong> {{ $summary['identity']['admin_email'] ?? 'Not set' }}</p>
        <a href="/onboard/{{ $tenant['token'] }}/identity">Edit</a>
    </div>

    <div class="w3-container w3-margin-top">
        <h4>Branding</h4>
        <p><strong>Primary Color:</strong> <span style="display:inline-block; width:20px; height:20px; background:{{ $summary['branding']['primary_color'] ?? '#ccc' }};"></span> {{ $summary['branding']['primary_color'] ?? 'Not set' }}</p>
        <p><strong>Secondary Color:</strong> <span style="display:inline-block; width:20px; height:20px; background:{{ $summary['branding']['secondary_color'] ?? '#ccc' }};"></span> {{ $summary['branding']['secondary_color'] ?? 'Not set' }}</p>
        <p><strong>Logo:</strong> @if($summary['branding']['logo_url'] ?? false) <img src="{{ $summary['branding']['logo_url'] }}" style="max-height:40px;"> @else Not uploaded @endif</p>
        <a href="/onboard/{{ $tenant['token'] }}/branding">Edit</a>
    </div>

    <div class="w3-container w3-margin-top">
        <h4>Rules</h4>
        <p><strong>Age Policy:</strong> {{ $summary['rules']['age_policy'] ?? 'Not set' }}</p>
        <p><strong>Enrollment Cycles:</strong> {{ count($summary['rules']['enrollment_cycles'] ?? []) }} defined</p>
        <a href="/onboard/{{ $tenant['token'] }}/rules">Edit</a>
    </div>

    <div class="w3-container w3-margin-top">
        <h4>Features</h4>
        <p><strong>Waitlist:</strong> {{ $summary['features']['waitlist_enabled'] ?? false ? 'Enabled' : 'Disabled' }}</p>
        <p><strong>Document Uploads:</strong> {{ $summary['features']['document_uploads'] ?? false ? 'Enabled' : 'Disabled' }}</p>
        <p><strong>Max Children:</strong> {{ $summary['features']['max_children_per_parent'] ?? 'Not set' }}</p>
        <a href="/onboard/{{ $tenant['token'] }}/features">Edit</a>
    </div>

    <div class="w3-container w3-margin-top">
        <h4>Content</h4>
        <p><strong>About Us:</strong> {{ Str::limit($summary['content']['about_us'] ?? '', 100) }}</p>
        <p><strong>Contact:</strong> {{ $summary['content']['contact_email'] ?? '' }} / {{ $summary['content']['contact_phone'] ?? '' }}</p>
        <a href="/onboard/{{ $tenant['token'] }}/content">Edit</a>
    </div>

    <form method="POST" action="/onboard/{{ $tenant['token'] }}/review/submit" class="w3-margin-top">
        <button class="w3-button w3-green" type="submit">Complete Onboarding</button>
    </form>
</div>