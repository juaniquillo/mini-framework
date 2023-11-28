<?php

namespace App\Cruds\Collections\Contact;

use App\Cruds\Collections\Contact\Inputs\EmailFactory;
use App\Cruds\Collections\Contact\Inputs\MessageFactory;
use App\Cruds\Collections\Contact\Inputs\NameFactory;
use Chatagency\CrudAssistant\Contracts\InputCollectionInterface;
use Chatagency\CrudAssistant\CrudAssistant;

class ContactCrud
{
    public  static function make() : InputCollectionInterface
    {
        return CrudAssistant::make([
            NameFactory::make(),
            EmailFactory::make(),
            MessageFactory::make(),
        ]);
    }
}
