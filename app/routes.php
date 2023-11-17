<?php

use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    
    view('home', [
        'foo' => 'var',
        /** @var Router $this */
        'title' =>  config('title'),
    ]);

});
