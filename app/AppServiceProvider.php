<?php

namespace App;

use Core\Application;

class AppServiceProvider
{
    public function __construct(
        protected Application $application
    ) 
    {
        $this->register();

        // $this->addGlobalParams();
    }
    
    protected function register()
    {
        include_once('helpers.php');
    }

    // public function addGlobalParams()
    // {
    //     $this->application
    //         ->addGlobalParam('foo', 'var')
    //         ->addGlobalParam('yo', 'wat');
    // }
}
