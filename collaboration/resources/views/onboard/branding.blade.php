<!-- resources/views/onboard/branding.blade.php -->
@extends('layouts.admin.app')

@section('title', 'Feature Settings')
@section('content')
@include('onboard._wizard')
<div class="w3-card w3-white w3-padding">
    <h3>Branding</h3>

    <form method="POST" action="/onboard/{{ $tenant['token'] }}/branding" enctype="multipart/form-data">
        <div class="w3-margin-bottom">
            <label>Primary Color</label>
            <input class="w3-input" type="color" name="primary_color" value="{{ $branding['primary_color'] }}">
        </div>

        <div class="w3-margin-bottom">
            <label>Secondary Color</label>
            <input class="w3-input" type="color" name="secondary_color" value="{{ $branding['secondary_color'] }}">
        </div>

        <div class="w3-margin-bottom">
            <label>Logo</label>
            <input class="w3-input" type="file" name="logo" accept="image/*">
            @if($branding['logo_url'])
                <div class="w3-margin-top">
                    <img src="{{ $branding['logo_url'] }}" alt="Logo" style="max-height: 80px;">
                </div>
            @endif
        </div>

        <button class="w3-button w3-blue" type="submit">Save & Continue</button>
    </form>
</div>
@endsection