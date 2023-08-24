<?php

/*
 * Is Closure
 */
use Core\Utils\General;

/*
 * Output
 */
if (! function_exists('dd')) {
    function dd($var)
    {
        General::dd($var);
    }
}
if (! function_exists('dump')) {
    function dump($var)
    {
        General::dump($var);
    }
}

/**
 * Source folder path
 */
if (! function_exists('root_path')) {
    function root_path()
    {
        return realpath(__DIR__.'/../../');
    }
}

/**
 * Env
 */
if (! function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
