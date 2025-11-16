<?php
include "includes/functions.php";

if ($_POST) {

    // Load data
    $parents = readJSON("data/parents.json");
    $children = readJSON("data/children.json");

    // Save Parent
    $parents[] = [
        "username" => $_POST['username'],
        "password" => $_POST['password'],
        "children" => []
    ];
    writeJSON("data/parents.json", $parents);

    // Save First Child Registered During Admission
    $children[] = [
        "parent" => $_POST['username'],
        "name" => $_POST['child_name'],
        "surname" => $_POST['child_surname'],
        "age" => $_POST['child_age'],
        "race" => $_POST['child_race'],
        "gender" => $_POST['child_gender'],
        "status" => "Awaiting approval"
    ];
    writeJSON("data/children.json", $children);

    echo "<p style='color:green; font-weight:bold;'>Registration Successful! You may now log in as a parent.</p>";
}
?>

<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>

<div class="container">
    <h2>Parent & Child Admission</h2>
    <p>Please complete the form below to register as a parent and enroll your first child.</p>

    <form method="POST">

        <h3>Parent Account Information</h3>
        <input name="username" placeholder="Create Username" required><br><br>
        <input name="password" type="password" placeholder="Create Password" required><br><br>

        <h3>Child Information</h3>
        <input name="child_name" placeholder="Child Name" required><br><br>
        <input name="child_surname" placeholder="Child Surname" required><br><br>
        <input name="child_age" placeholder="Child Age" required><br><br>
        <input name="child_race" placeholder="Race" required><br><br>
        <input name="child_gender" placeholder="Gender" required><br><br>

        <button class="button">Register Parent & Child</button>
    </form>

</div>
