<?php

namespace Core;

use Dotenv\Dotenv;
use Core\MainContainer;
use Core\Contracts\Router;
use App\AppServiceProvider;
use Core\Contracts\Template;
use Core\Contracts\Container;
use Bayfront\HttpRequest\Request;
use Core\Utils\General;

class Application
{
    /**
     * Container
     */
    private static Container $container;
    
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

    public static function resolve(string $name) : object
    {
       return self::$container->resolve($name);
    }
  
    /**
     * Bootstraps all services
     *
     * @return self
     */
    public function bootstrap() : self
    {
        /**
         * Container
         */
        self::$container = new MainContainer();
        
        /**
         * Envs
         */
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        self::$container->bind($dotenv);

        /**
         * Helpers
         */
        include_once(realpath(__DIR__.'/Utils/helpers.php'));

        /**
         * Config
         */
        $defaults = include(realpath(__DIR__.'/Defaults/config-defaults.php'));
        $appConfig = include(realpath(__DIR__.'/../app/config.php'));

        $config = MainConfig::make($defaults)
            ->set($appConfig);
       
        /**
         * Template Engine
         */
        $template = MainTemplate::make();

        $template
            ->setAdditionalParams($this->globalTemplateParams)
            ->setViewsDirectory($config->get('views.directory'));
        
        /**
         * Router
         */
        $router = MainRouter::make(
            $config,
            $template,
        );

        
        /**
         * Provider
         */
        // $this->appProvider = new AppServiceProvider($this);

        self::$container->bind($config, 'config');
        self::$container->bind($template, 'template');
        self::$container->bind($router, 'router');
        
        return $this;
        
    }

    /**
     * Runs application
     *
     * @return void
     */
    public function run() : void
    {
        General::include(function(){
            include(__DIR__.'/../app/routes.php');
        });
        
        self::$container->resolve('router')->run();
    }

    public function __destruct() 
    {
        
    }

}
