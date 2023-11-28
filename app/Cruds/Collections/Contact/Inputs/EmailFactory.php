<?php

namespace App\Cruds\Collections\Contact\Inputs;

use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\Inputs\TextInput;

class EmailFactory
{
    const NAME = 'email';

    const LABEL = 'Email';
    
    public  static function make() : InputInterface
    {
        $input = new TextInput(self::NAME, self::LABEL);

        $input->setType('email');

        return $input;
    }
}
