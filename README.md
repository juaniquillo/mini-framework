# MiniFramework

## Environment

The framework uses the package [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) for environment secret's management.

Environment are stored in the `.env` file. Use the `ENV[$key]`super global or the `env($key, $default = null)` global function to access the individual environment values.

## Routes

For routes the package [bramus/router](https://github.com/bramus/router) is used.

Add routes in `app/routes.php` calling the verb method, with the path as a first parameter and a closure as a second parameter:

```php
$this->get('/', function (){
    // root
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

To render the view use the `template` and the `render` method inside a route's closure. The first parameter must be the name of view's file (without the `.php`) and the second parameter is an array with available arguments that will become available inside the view:

```php
use Core\Application;

$router = Application::resolve('router');

$router->get('/', function() { 
    
    view('home', [
        'foo' => 'var',
        /** @var Router $this */
        'title' =>  config('title'),
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

To access inside a route use the `config` and the `get` method.

```php
$this->get('/', function (){
    
    $title = $this->config->get('title');

});
```

## Todo

- Middleware
- Models
- Request
- IoC Container