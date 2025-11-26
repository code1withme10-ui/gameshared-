<?php

use App\Helpers\ContentHistory;
use App\Helpers\ContentLoader;
use App\Helpers\ContentRenderer;

require __DIR__ . '/vendor/autoload.php';

$history = new ContentHistory(__DIR__ . '/storage');
$loader  = new ContentLoader(__DIR__ . '/data/jokes', $history);
$renderer = new ContentRenderer(__DIR__ . '/app/Templates/templates');

$selected = $loader->getRandomContent();
echo $renderer->render($selected);
