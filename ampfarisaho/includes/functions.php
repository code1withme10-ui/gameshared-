<?php
function readJSON($file) {
if (!file_exists($file)) {
return [];
}
$json = file_get_contents($file);
return json_decode($json, true) ?: [];
}


function writeJSON($file, $data) {
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
?>