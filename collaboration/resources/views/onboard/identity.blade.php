<!-- resources/views/onboard/identity.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <title>Onboarding — Identity</title>
  
  @include('onboard.head')
</head>
<body class="w3-light-grey">

<div class="w3-container w3-card w3-white w3-margin">
  <h2>Crèche Identity & Ownership</h2>
@include('onboard._wizard')
  <form class="w3-container" id="identityForm">
    <label>Legal Name</label>
    <input class="w3-input" name="legal_name">

    <label>Display Name</label>
    <input class="w3-input" name="display_name">

    <label>Registration Type</label>
    <select class="w3-select" name="registration_type">
      <option value="npo">NPO</option>
      <option value="company">Company</option>
      <option value="private">Private</option>
    </select>

    <label>Registration Number</label>
    <input class="w3-input" name="registration_number">

    <label>Admin Email</label>
    <input class="w3-input" type="email" name="admin_email">

    <button class="w3-button w3-blue w3-margin-top">
      Save & Continue
    </button>
  </form>
</div>

<script>
// future API call placeholder
</script>
</body>
</html>
