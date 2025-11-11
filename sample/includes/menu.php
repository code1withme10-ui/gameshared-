
<div class="w3-bar w3-light-grey w3-border">
  <a href="?page=home" class="w3-bar-item w3-button">Home</a>
  <a href="?page=about" class="w3-bar-item w3-button">About Us</a>
  <a href="?page=gallery" class="w3-bar-item w3-button">Gallery</a>
  <a href="?page=admission" class="w3-bar-item w3-button">Admission</a>
  <a href="?page=contact" class="w3-bar-item w3-button">Contact</a>
  
  <?php if (isset($_SESSION['parent'])): ?>
  <a href="?page=logout" class="w3-bar-item w3-button w3-right">Logout</a>
<?php else: ?>
  <a href="?page=login" class="w3-bar-item w3-button w3-right">Login</a>
  <a href="?page=register" class="w3-bar-item w3-button w3-right">Register</a>
<?php endif; ?>

  
</div>
