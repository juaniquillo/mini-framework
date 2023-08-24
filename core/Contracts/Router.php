<?php

namespace Core\Contracts;

use Closure;

interface Router
{
    public static function make(...$args) : static;

    public function setConfig(Config $config) : self;
    
    public function setTemplate(Template $template) : self;

    public function setRequest($request) : self;

    public function includeRoutes(string $path) : void;

    public function get(string $route, Closure $closure) : self;

    public function post(string $route, Closure $closure) : self;

    public function put(string $route, Closure $closure) : self;

    public function patch(string $route, Closure $closure) : self;

    public function delete(string $route, Closure $closure) : self;

    public function run() : void;

}
