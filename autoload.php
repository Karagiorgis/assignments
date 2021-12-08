<?php

spl_autoload_register(function($classname) {
    $filepath = __DIR__ . '/' . lcfirst(str_replace("\\", "/", $classname)) . '.php';

    if (file_exists($filepath)) {
        require_once $filepath;
    }
});
