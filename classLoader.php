<?php
spl_autoload_register(
    static function (string $class) {
        $classFileFullPath = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
        if (!file_exists($classFileFullPath)) {
            throw new \Exception("Class {$class} could not find");
        }
        require_once $classFileFullPath;
    }
);
