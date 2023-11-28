<?php

namespace App\Cruds\Actions\Form\Accessors;

use Chatagency\CrudAssistant\Contracts\InputInterface;

interface AccessorInterface
{
    public function get(InputInterface $input, $params, $recipe = null, $data = null);
}
