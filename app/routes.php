<?php

use App\Cruds\Actions\Form\InputPresenterAction;
use App\Cruds\Collections\Contact\ContactCrud;
use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    
    $crud = ContactCrud::make();

    $inputs = $crud->execute(
        InputPresenterAction::make()
    );

    view('home', [
        'title' =>  config('title'),
        'foo' => 'fill out the form',
        'inputs' => $inputs,
    ]);

});

$router->post('/', function() { 
    
    $crud = ContactCrud::make();

    $inputs = $crud->execute(
        InputPresenterAction::make()
    );

    dd($inputs);

});


