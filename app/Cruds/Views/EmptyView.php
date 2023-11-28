<?php

namespace App\Cruds\Views;

class EmptyView extends ComponentTemplate
{
    /**
     * Construct can receive a data array.
     *
     * @return self
     */
    public function __construct(array $data = [])
    {
        /**
         * Future prove
         */
        $this->setType('empty');

        return parent::__construct($data);
    }
}
