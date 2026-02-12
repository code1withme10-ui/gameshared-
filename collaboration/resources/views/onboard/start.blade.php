 
@extends('layouts.admin.app')

@section('title', 'Start Onboarding')
@section('content')
 
<div class="w3-container w3-center w3-padding-64">
  <div class="w3-card w3-white w3-padding-32 w3-round-large" style="max-width:500px;margin:auto">

    <h2 class="w3-text-blue">Crèche Onboarding</h2>
    <p>
      This wizard will guide you through registering a new crèche
      and generating its website and portal.
    </p>

    <a href="/onboard/{{ uniqid() }}/identity"
       class="w3-button w3-blue w3-margin-top">
      Start Onboarding
    </a>

    <p class="w3-small w3-text-grey w3-margin-top">
      You can save progress and return at any time.
    </p>
  </div>
</div>
@endsection
