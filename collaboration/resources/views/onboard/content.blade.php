@extends('layouts.admin.app')

@section('title', 'Feature Settings')
@section('content')
@include('onboard._wizard')
<div class="w3-card w3-white w3-padding">
    <h3>Static Content</h3>
    <p>Information that will appear on your public website.</p>

    <form method="POST" action="/onboard/{{ $tenant['token'] }}/content">
        <div class="w3-margin-bottom">
            <label>About Us</label>
            <textarea class="w3-input" name="about_us" rows="4">{{ $content['about_us'] }}</textarea>
        </div>

        <div class="w3-margin-bottom">
            <label>Admission Guidelines</label>
            <textarea class="w3-input" name="admission_guide" rows="4">{{ $content['admission_guide'] }}</textarea>
        </div>

        <div class="w3-margin-bottom">
            <label>Contact Email</label>
            <input class="w3-input" type="email" name="contact_email" value="{{ $content['contact_email'] }}">
        </div>

        <div class="w3-margin-bottom">
            <label>Contact Phone</label>
            <input class="w3-input" type="tel" name="contact_phone" value="{{ $content['contact_phone'] }}">
        </div>

        <div class="w3-margin-bottom">
            <label>Address</label>
            <input class="w3-input" name="address" value="{{ $content['address'] }}">
        </div>

        <button class="w3-button w3-blue" type="submit">Save & Continue</button>
    </form>
</div>
@endsection