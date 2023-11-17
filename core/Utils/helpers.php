<?php

/*
 * Is Closure
 */
use Core\Application;
use Core\Contracts\Config;
use Core\Contracts\Router;
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
 * Closure utilities
 */
if (! function_exists('is_closure')) {
    function is_closure($t) {
        return $t instanceof \Closure;
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

/**
 * Config
 */
if (! function_exists('config')) {
    function config($name, $default = null) : mixed
    {
        /** @var Config $config */
        $config = Application::resolve('config');
        return  $config->get($name, $default);
    }
}

/**
 * View
 */
if (! function_exists('view')) {
    function view(string $name, array $params = []) : Router
    {
        return  Application::resolve('template')
            ->render($name, $params);
    }
}
