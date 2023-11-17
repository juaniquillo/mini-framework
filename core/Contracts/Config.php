<?php

namespace Core\Contracts;

interface Config
{
    public static function make(...$args) : static;

    /**
     * Set a config array
     *
     * @return  self
     */ 
    public function set(array $config) : self;

    public function get($key, $default = null) : mixed;

    public function all() : array;
}
