
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

  <?php if (isset($_SESSION['role'])): ?>
    <a href="?page=logout" class="w3-button w3-red w3-small w3-right">Logout</a>
<?php endif; ?>
<div class="w3-bar w3-rainbow">
  
    
    <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'parent'): ?>
            <a href="?page=parent_dashboard" class="w3-bar-item w3-button">Dashboard</a>
        <?php elseif ($_SESSION['role'] === 'headmaster'): ?>
            <a href="?page=headmaster" class="w3-bar-item w3-button">Headmaster Portal</a>
        <?php endif; ?>
        
    <?php else: ?>
         
    <?php endif; ?>
</div>

</div>
