<?php

use Core\MainRouter;

/** @var MainRouter $this */
$this->get('/', function() { 

    /** @var MainRouter $this */
    $this->template->render('home', [
        'foo' => 'var',
        /** @var MainRouter $this */
        'title' => $this->config->get('title'),
    ]);

});
