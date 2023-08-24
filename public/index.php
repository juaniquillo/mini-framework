<?php

use Core\Application;

include_once('../vendor/autoload.php');

$app = Application::make()
    ->bootstrap()
    ->run();