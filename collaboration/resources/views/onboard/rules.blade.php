@extends('layouts.admin.app')

@section('title', 'Onboarding — Identity')
@section('content')
 
@include('onboard._wizard')

<div class="w3-card w3-white w3-padding">
    <h3>Enrollment Rules</h3>

    <form method="POST" action="/onboard/{{ $tenant['token'] }}/rules">
        <div class="w3-margin-bottom">
            <label>Age policy</label>
            <select name="age_policy" class="w3-select">
                <option value="must_be_2_by_december" {{ $rules['age_policy'] == 'must_be_2_by_december' ? 'selected' : '' }}>
                    Must be 2 by 31 December
                </option>
                <option value="must_be_3_by_march" {{ $rules['age_policy'] == 'must_be_3_by_march' ? 'selected' : '' }}>
                    Must be 3 by 31 March
                </option>
            </select>
        </div>

        <h4>Enrollment Cycles</h4>
        <div id="cycles">
            @foreach($rules['enrollment_cycles'] as $cycle)
            <div class="w3-border w3-padding w3-margin-bottom">
                <input class="w3-input" name="cycles[][name]" value="{{ $cycle['name'] }}" placeholder="Term name">
                <input class="w3-input" name="cycles[][start]" value="{{ $cycle['start'] }}" placeholder="Start date">
                <input class="w3-input" name="cycles[][end]" value="{{ $cycle['end'] }}" placeholder="End date">
            </div>
            @endforeach
        </div>

        <button type="button" class="w3-button w3-blue" onclick="addCycle()">Add Cycle</button>
        <button class="w3-button w3-green" type="submit">Save & Continue</button>
    </form>
</div>

<script>
function addCycle() {
    const div = document.createElement('div');
    div.className = 'w3-border w3-padding w3-margin-bottom';
    div.innerHTML = `
        <input class="w3-input" name="cycles[][name]" placeholder="Term name">
        <input class="w3-input" name="cycles[][start]" placeholder="Start date">
        <input class="w3-input" name="cycles[][end]" placeholder="End date">
    `;
    document.getElementById('cycles').appendChild(div);
}
</script>
@endsection