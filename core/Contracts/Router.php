<?php

namespace Core\Contracts;

use Closure;

interface Router
{
    public static function make(...$args) : static;

    public function setRequest($request) : self;

    public function get(string $route, Closure $closure) : self;

    public function post(string $route, Closure $closure) : self;

    public function put(string $route, Closure $closure) : self;

    public function patch(string $route, Closure $closure) : self;

    public function delete(string $route, Closure $closure) : self;

    public function set404(Closure $closure = null) : self;

    public function run() : void;

}
