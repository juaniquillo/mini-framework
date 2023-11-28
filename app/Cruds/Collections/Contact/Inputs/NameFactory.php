<?php

namespace App\Cruds\Collections\Contact\Inputs;

use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\Inputs\TextInput;

class NameFactory
{
    const NAME = 'name';

    const LABEL = 'Name';
    
    public  static function make() : InputInterface
    {
        $input = new TextInput(self::NAME, self::LABEL);

        return $input;
    }
}
