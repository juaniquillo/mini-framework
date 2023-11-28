<?php

namespace App\Cruds\Actions\Table\Options;

use Chatagency\CrudAssistant\DataContainer;

/**
 * Options Base
 */
abstract class Option
{
    /*
     * Option Data
     *
     * @var DataContainer
     */
    protected $data = null;

    /**
     * Construct for dependency injection
     *
     * @param  DataContainer  $data
     */
    public function __construct(DataContainer $data = null)
    {
        $this->data = $data;
    }

    /**
     * Returns option data
     *
     * @return DataContainer|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Creates option
     *
     * @param $content
     * @param  DataContainer  $data
     * @return DataContainer
     */
    abstract public function create($content, DataContainer $data = null);
}
