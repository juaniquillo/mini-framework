# MiniFramework

An exercise on who to make an MVC framework from (semi) scratch.

## Environment

The framework uses the package [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) for environment secret's management.

Environment are stored in the `.env` file. Use the `ENV[$key]`super global or the `env($key, $default = null)` global function to access the individual environment values.

## Container

A simple IoC container. 

Objects can be bound to the container:

```php
Application::bind(
    $object, 
    $name // opcional
);
```

For complex objects a `Closure` can be used:

```php
Application::bind(function(){
    return (new Object())
        ->doSomethingElse();
});
```

Objects can be resolved:

Or using the helper:

```php
Application::resolve($name);
```

Here's the list of objects bound by the `Application`:

- env
- config
- template
- router

The can be resolved using the keyword:

```php
Application::resolve('config');
```

## Routes

For routes the package [bramus/router](https://github.com/bramus/router) is used.

Add routes in `app/routes.php` calling the verb method, with the path as a first parameter and a closure as a second parameter:

```php
use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    // your code here
});
```

Methods available:

 - get
 - post
 - put
 - patch
 - delete

## Views 

Views use the package [thephpleague/plates](https://platesphp.com/).

You can add views inside the `resources\Views` folder. 

To render the view the `view` helper can be used. The first parameter must be the name of view's file (without the `.php`) and the second parameter is an array with available arguments that will become available inside the view:

```php
use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    
    view('home', [
        'foo' => 'var',
    ]);

});
```
- [Layouts](https://platesphp.com/templates/layouts/)
- [Sections](https://platesphp.com/templates/sections/)

## Config

Configuration values can be stored in the `App/config.php` file. Environment values can be used inside for composing values:

```php 
return [
    'title' => env('TITLE', 'MiniFramework 2'),
];
```

To access the `config` the helper can be used.

```php
$this->get('/', function (){
    
    $title = config('title');

});
```

## Todo

- Middleware
- Models
- Request
- Singletons for the container
- 404 view