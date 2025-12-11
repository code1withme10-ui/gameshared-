<?php
/**
 * Simple autoloader for app classes
 * 
 * Place your Controllers in app/Http/
 * Place your Models in app/Models/
 */

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/Http/{$class}.php",     // Controllers
        __DIR__ . "/Models/{$class}.php"    // Models
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
