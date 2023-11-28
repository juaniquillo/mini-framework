<?php

namespace App\Cruds\Collections\Contact\Inputs;

use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\Inputs\TextareaInput;

class MessageFactory
{
    const NAME = 'message';

    const LABEL = 'Message';
    
    public  static function make() : InputInterface
    {
        $input = new TextareaInput(self::NAME, self::LABEL);

        return $input;
    }
}
