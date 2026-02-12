@extends('layouts.admin.app')

@section('title', 'Feature Settings')
@section('content')
@include('onboard._wizard')
<div class="w3-card w3-white w3-padding">
    <h3>Feature Settings</h3>

    <form method="POST" action="/onboard/{{ $tenant['token'] }}/features">
        <div class="w3-margin-bottom">
            <label>
                <input type="checkbox" name="waitlist_enabled" value="1"
                    {{ $features['waitlist_enabled'] ? 'checked' : '' }}>
                Enable waitlist
            </label>
        </div>

        <div class="w3-margin-bottom">
            <label>
                <input type="checkbox" name="document_uploads" value="1"
                    {{ $features['document_uploads'] ? 'checked' : '' }}>
                Allow document uploads
            </label>
        </div>

        <div class="w3-margin-bottom">
            <label>Max children per parent</label>
            <select name="max_children_per_parent" class="w3-select">
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $features['max_children_per_parent'] == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <button class="w3-button w3-blue" type="submit">Save & Continue</button>
    </form>
</div>
@endsection