<?php
// Create "data" folder if it doesn't exist
if (!is_dir("data")) {
    mkdir("data", 0777, true);
}

// File where JSON data will be saved
$file = "data/progress.json";

// Collect submitted form data
$entry = [
    "name"         => $_POST["name"] ?? "",
    "date"         => $_POST["date"] ?? "",
    "project"      => $_POST["project"] ?? "",
    "tasks"        => $_POST["tasks"] ?? "",
    "hours"        => $_POST["hours"] ?? "",
    "status"       => $_POST["status"] ?? "",
    "challenges"   => $_POST["challenges"] ?? "",
    "need_help"    => $_POST["need_help"] ?? "",
    "help_details" => $_POST["help_details"] ?? "",
    "productivity" => $_POST["productivity"] ?? "",
    "notes"        => $_POST["notes"] ?? ""
];

// If file exists, load JSON data — otherwise create a new array
if (file_exists($file)) {
    $jsonData = json_decode(file_get_contents($file), true);
    if (!$jsonData) {
        $jsonData = [];  // repair corrupted/empty file
    }
} else {
    $jsonData = [];
}

// Add new entry to array
$jsonData[] = $entry;

// Save back to JSON file (pretty formatted)
file_put_contents($file, json_encode($jsonData, JSON_PRETTY_PRINT));

// Confirmation message
echo "<h2>Progress submitted successfully!</h2>";
echo "<p><a href='index.php'>Submit another</a></p>";
echo "<p><a href='admin.php'>View all progress</a></p>";
?>
