<?php

namespace Core;

use Dotenv\Dotenv;
use Bayfront\HttpRequest\Request;
use App\AppServiceProvider;
use Core\Contracts\Config;
use Core\Contracts\Container;
use Core\Contracts\Router;
use Core\Contracts\Template;

class Application
{
    /**
     * Container
     */
    public static Container $container;
    
    /**
     * Config
     *
     * @var Config
     */
    protected Config $config;

    /**
     * Router
     *
     * @var Router
     */
    protected Router $router;

    /**
     * App service provider
     *
     * @var AppServiceProvider
     */
    protected AppServiceProvider $appProvider;

    /**
     * Global Template Params
     *
     * @var array
     */
    protected array $globalTemplateParams = [];

    /**
     * Template
     *
     * @var Template
     */
    protected Template $template;

    /**
     * Make
     *
     * @param ...$args
     * 
     * @return static 
     */
    public static function make() : static
    {
        return new static();
    }
    
    public function addGlobalParam(string $key, $value) : self
    {
        $this->globalTemplateParams[$key] = $value;
        
        return $this;
    }
    
    public function setTemplateEngine(Template $template) : self
    {
        $this->template = $template;
        
        return $this;
    }
    
    public function setRouter(Router $router) : self
    {
        $this->router = $router;
        
        return $this;
    }

    /**
     * Bootstraps all services
     *
     * @return self
     */
    public function bootstrap() : self
    {
        /**
         * Envs
         */
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        /**
         * Helpers
         */
        include_once(realpath(__DIR__.'/Utils/helpers.php'));

        /**
         * Config
         */
        $defaults = include(realpath(__DIR__.'/Defaults/config-defaults.php'));
        $appConfig = include(realpath(__DIR__.'/../app/config.php'));

        $this->config = MainConfig::make($defaults)
            ->set($appConfig);
       
        /**
         * Template Engine
         */
        $this->template = MainTemplate::make();

        $this->template
            ->setAdditionalParams($this->globalTemplateParams)
            ->setViewsDirectory($this->config->get('views.directory'));
        
        /**
         * Router
         */
        $this->router = MainRouter::make(
            $this->config,
            $this->template,
        );
        
        /**
         * Provider
         */
        $this->appProvider = new AppServiceProvider($this);

        /**
         * Include routes
         */
        $this->router->includeRoutes(__DIR__.'/../app/routes.php');
        
        return $this;
        
    }

    /**
     * Runs application
     *
     * @return void
     */
    public function run() : void
    {
        $this->router->run();
    }

    public function __destruct() 
    {
        
    }

}
