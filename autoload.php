<?php

class Autoloader {
    protected static $fileIterator = null;

    public static function loader($className){
        $directory = new RecursiveDirectoryIterator("vendor/", RecursiveDirectoryIterator::SKIP_DOTS);
        if (is_null(static::$fileIterator)) {
            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }
        $filename = $className . '.class.php';
        foreach (static::$fileIterator as $file) {
            if (strtolower($file->getFilename()) === strtolower($filename)) {
                if ($file->isReadable())
                    require_once $file->getPathname();
                break;
            }
        }
    }
}

class AutoloaderModels {
    protected static $fileIterator = null;
    public static function loader($className){
        $directory = new RecursiveDirectoryIterator("models/", RecursiveDirectoryIterator::SKIP_DOTS);
        if (is_null(static::$fileIterator)) {
            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }
        $filename = $className . '.php';
        foreach (static::$fileIterator as $file) {
            if (strtolower($file->getFilename()) === strtolower($filename)) {
                if ($file->isReadable())
                    require_once $file->getPathname();
                break;
            }
        }
    }
}

class AutoloaderControllers {
    protected static $fileIterator = null;
    public static function loader($className){
        $directory = new RecursiveDirectoryIterator("controllers/", RecursiveDirectoryIterator::SKIP_DOTS);
        if (is_null(static::$fileIterator)) {
            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }
        $filename = $className . '.php';
        foreach (static::$fileIterator as $file) {
            if (strtolower($file->getFilename()) === strtolower($filename)) {
                if ($file->isReadable())
                    require_once $file->getPathname();
                break;
            }
        }
    }
}

spl_autoload_register('Autoloader::loader');
spl_autoload_register('AutoloaderModels::loader');
spl_autoload_register('AutoloaderControllers::loader');