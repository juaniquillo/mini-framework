<?php

namespace Core\Contracts;

interface Template
{
    public static function make(...$args) : static;

    public function setViewsDirectory(string $directory) : self;

    public function render(string $view, $params = []);

    public function mergeAdditionalParams(array $params) : array;

    /**
     * Set additional Param
     *
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public function setAdditionalParam(string $key, $value);

    /**
     * Set the value of additionalParams
     * 
     * @return  self
     */ 
    public function setAdditionalParams(array $additionalParams);
}
