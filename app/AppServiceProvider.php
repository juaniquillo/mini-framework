<?php

namespace App;

use Core\Application;

class AppServiceProvider
{
    public function __construct(
        protected Application $application
    ) {
        $this->register();

    }
    
    protected function register()
    {
        include_once('helpers.php');
    }

}
