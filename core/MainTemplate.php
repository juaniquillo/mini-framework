<?php

namespace Core;

use Closure;
use League\Plates\Engine;
use Core\Contracts\Template;

class MainTemplate implements Template
{
    protected array $additionalParams = [];

    protected Engine $engine;
    
    public function __construct()
    {
        $this->engine = new Engine();
        
        return $this;
    }

    public static function make(...$args) : static
    {
        return new static(...$args);
    }

    public function setViewsDirectory(string $directory) : self
    {
        $this->engine->setDirectory($directory);
        
        return $this;
    }

    public function render(string $view, $params = []) 
    {
        echo $this->engine->render($view, $this->mergeAdditionalParams($params));

        $this->destroy();
    }

    public function mergeAdditionalParams(array $params) : array
    {
        return array_merge($this->additionalParams, $params);
    }

    public function setHelper(string $name, Closure $closure) : self
    {
        $this->engine->registerFunction($name, $closure);

        return $this;
    }

    /**
     * Set additional Param
     *
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public function setAdditionalParam(string $key, $value)
    {
        $this->additionalParams[$key] = $value;

        return $this;
    }

    /**
     * Set the value of additionalParams
     * 
     * @return  self
     */ 
    public function setAdditionalParams(array $additionalParams)
    {
        foreach ($additionalParams as $key => $value) {
            $this->setAdditionalParam($key, $value);
        }

        return $this;
    }

    protected function destroy() : never
    {
        exit;
    }

}
