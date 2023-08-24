<?php

namespace Core;

use Adbar\Dot;
use Core\Contracts\Config;

class MainConfig implements Config
{
    public function __construct(
        protected $config = []
    ) 
    {}

    public static function make(...$args) : static
    {
        return new static(...$args);
    }

    /**
     * Set a config array
     *
     * @return  self
     */ 
    public function set(array $config) : self
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function get($key) : mixed
    {
        /**
         * For dot notation, ej. 'folder.subfolder'
         */
        $dot = new Dot($this->config);
        
        return $dot->get($key);
    }

    public function all() : array
    {
        return $this->config;
    }
}
