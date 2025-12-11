<?php
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/Http/' . $class . '.php',   // Controllers
        __DIR__ . '/Models/' . $class . '.php' // Models
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
