<?php

namespace Core\Contracts;

interface Container
{
    public function bind(object $instance, $name = null) : self;

    public function resolve(string $name) : object;

    public function isset(string $name) : bool;

    public function get(string $name) : object;
}
