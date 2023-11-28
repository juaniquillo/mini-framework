<?php

namespace Core;

use Bayfront\HttpRequest\Request;
use Closure;
use Bramus\Router\Router;
use Core\Contracts\Router as RouterContract;
use Core\Contracts\Template;

class MainRouter implements RouterContract
{
    protected $router;
    
    public function __construct(
        protected readonly Request $request = new Request,
    ) 
    {
        $this->router = new Router();
    }

    public static function make(...$args) : static
    {
        return new static(...$args);
    }

    /**
     * Set the value of request
     *
     * @return self
     */ 
    public function setRequest($request) : self
    {
        $this->request = $request;

        return $this;
    }
  
    public function get(string $route, Closure $closure) : self
    {
        
        $this->router->get($route, $closure);

        return $this;
    }

    public function post(string $route, Closure $closure) : self
    {
        $this->router->post($route, $closure);

        return $this;
    }
    
    public function put(string $route, Closure $closure) : self
    {
        $this->router->put($route, $closure);

        return $this;
    }

    public function patch(string $route, Closure $closure) : self
    {
        $this->router->patch($route, $closure);

        return $this;
    }

    public function delete(string $route, Closure $closure) : self
    {
        $this->router->delete($route, $closure);

        return $this;
    }

    public function run() : void
    {
        $this->router->run();
    }

    public function set404(Closure $closure = null) : self
    {
        if($closure) {
            $this->router->set404($closure);
            return $this;
        }
        
        $this->router->set404(function() {
        
            header('HTTP/1.1 404 Not Found');
            
            view('codes/404', []);
        });
        
        return $this;
    }

}

