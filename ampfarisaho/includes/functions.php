<?php
function readJSON($file) {
    return json_decode(file_get_contents($file), true);
}

function writeJSON($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
?>
