<?php

require_once __DIR__ . '/../services/JsonStorage.php';

$storage = new JsonStorage(__DIR__ . '/../../storage/notices.json');
$notices = $storage->read();
