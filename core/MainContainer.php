<?php

namespace Core;

use Core\Contracts\Container;

class MainContainer implements Container
{
    private array $instances = [];
    
    /**
     * Binds instance to container
     *
     * @return self
     */
    public function bind(object $instance, $name = null) : self
    {
        $this->instances[$name ?? get_class($instance)] = $instance;

        return $this;
    }

    /**
     * Resolves instance
     *
     * @param string $name
     * 
     * @return object
     * 
     * @throws \Exception
     */
    public function resolve(string $name) : object
    {
        if($this->isset($name)) {
            
            if(is_closure($name)) {
                $this->bind($name());
            }

            return $this->get($name);
        }
        
        if(!class_exists($name)) {
            throw new \Exception('Class could not be resolved', 500);
        }

        if(!class_exists($name)) {
            throw new \Exception('The class '.$name.' does not exist', 500);
        }

        $instance = new $name;
        $this->bind($instance);

        return $instance;
    }

    /**
     * Does instance exists
     *
     * @param string $name
     * 
     * @return boolean
     */
    public function isset(string $name) : bool
    {
        return isset($this->instances[$name]);
    }

    /**
     * Returns instance or null
     *
     * @param string $name
     * 
     * @return object|null
     */
    public function get(string $name) : object
    {
        return $this->instances[$name] ?? null;
    }
}
