<?php

use App\Cruds\Actions\Form\InputPresenterAction;
use App\Cruds\Collections\Contact\ContactCrud;
use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    
    view('home', [
        'title' =>  config('title'),
    ]);

});

