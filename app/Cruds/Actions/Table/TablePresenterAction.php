<?php

namespace App\Cruds\Actions\Table;

use App\Cruds\Actions\Table\Options\Option;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\Action;
use Chatagency\CrudAssistant\Contracts\ActionInterface;
use Chatagency\CrudAssistant\Contracts\DataContainerInterface;
use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\CrudAssistant;
use Chatagency\CrudAssistant\DataContainer;
use Closure;
use InvalidArgumentException;

/**
 * Table presenter action.
 */
class TablePresenterAction extends Action implements ActionInterface
{
    /**
     * Controls recursion
     *
     * @var bool
     */
    protected $controlsRecursion = true;

    /**
     * Action control the
     * whole execution
     *
     * @var bool
     */
    protected $controlsExecution = true;

    /**
     * Process internal collection
     *
     * @var bool
     */
    protected $processInternalCollection = false;

    /**
     * Model.
     *7
     */
    protected $model;

    /**
     * Pagination.
     *
     * @var Closure
     */
    protected $pagination;

    /**
     * Count.
     *
     * @var Closure
     */
    protected $count;

    /**
     * Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Options Head
     *
     * @var array
     */
    protected $optionHeads = [];

    /**
     * Prepend Columns
     *
     * @var array
     */
    protected $prependColumns = [];

    /**
     * Append Columns
     *
     * @var array
     */
    protected $appendColumns = [];

    /**
     * Prepend Content
     *
     * @var string|ComponentTemplate
     */
    protected $prependContent;

    /**
     * Prepend Content
     *
     * @var string|ComponentTemplate
     */
    protected $appendContent;

    /**
     * Last Columns
     *
     * @var array
     */
    protected $lastColumns = [];

    /**
     * Returns arrays instead of 
     * ComponentTemplate classes
     *
     * @var string
     */
    protected $arrayContainer = false;

    /**
     * Pre process json column
     *
     * @var string
     */
    protected $preProcessJson;

    /**
     * Header.
     *
     * @var array
     */
    protected $header = [];

    /**
     * Body.
     *
     * @var array
     */
    protected $body = [];

    /**
     * Set the value of model
     *
     * @param $model
     * 
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set the value of pagination
     *
     * @param  Closure  $pagination
     * @return  self
     */
    public function setPagination(Closure $pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Set the value of count
     *
     * @return  self
     */
    public function setCount(Closure $count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Set the value of options
     *
     * @return  self
     */
    public function setOptions(array $options, string $title = null, $key = null)
    {
        foreach ($options as $option) {
            $this->setOption($option, $title, $key);
        }

        return $this;
    }

    /**
     * Adds option to the options array
     *
     * @param  Option  $option
     * @return self
     */
    public function setOption(Option $option, string $title = null, $key = null)
    {
        $key = $key ?? 0;

        $this->options[$key][] = $option;

        if($title) {
            $this->optionHeads[$key] = $title;
        }
        
        return $this;
    }

    /**
     * Set data-container, array
     *
     * @param  string  $arrayContainer  data-container, array
     *
     * @return  self
     */ 
    public function setArrayContainer()
    {
        $this->arrayContainer = true;

        return $this;
    }

    /**
     * Set the value of prependColumns
     *
     * @return  self
     */
    public function prependColumns(array $prependColumns)
    {
        $this->prependColumns = $prependColumns;

        return $this;
    }

    /**
     * Set the value of appendColumns
     *
     * @return  self
     */
    public function appendColumns(array $appendColumns)
    {
        $this->appendColumns = $appendColumns;

        return $this;
    }

    /**
     * Set the value of lastColumns
     *
     * @return  self
     */
    public function lastColumns(array $lastColumns)
    {
        $this->lastColumns = $lastColumns;

        return $this;
    }

    /**
     * Set prepend Content
     *
     * @param  string|ComponentTemplate  $prependContent  Prepend Content
     * @return  self
     */
    public function prependContent($prependContent)
    {
        $this->prependContent = $prependContent;

        return $this;
    }

    /**
     * Set append Content
     *
     * @param  string|ComponentTemplate  $appendContent  Prepend Content
     * @return  self
     */
    public function appendContent($appendContent)
    {
        $this->appendContent = $appendContent;

        return $this;
    }

    /**
     * Sets pre process json column
     *
     * @param  string  $key
     * @return self
     */
    public function setPreProcessJson(string $key)
    {
        $this->preProcessJson = $key;

        return $this;
    }

    /**
     * Pre Execution.
     *
     * @return self
     */
    public function prepare()
    {
        if (! $this->model) {
            throw new InvalidArgumentException('The model is required', 500);
        }

        return parent::prepare();
    }

    /**
     * Execute action on input.
     *
     * @return DataContainerInterface
     */
    public function execute(InputInterface $input)
    {
        $collection = $input;

        $model = $this->model;

        $pagination = $this->pagination ?? null;
        $options = $this->options ?? null;
        $prependColumns = $this->prependColumns;
        $appendColumns = $this->appendColumns;
        $lastColumns = $this->lastColumns;
        $prependContent = $this->prependContent;
        $appendContent = $this->appendContent;
        $count = $this->count ?? count($model);
        $body = [];

        if (is_callable($pagination)) {
            $pagination = $pagination($collection, $model);
        }

        if (is_callable($count)) {
            $count = $count($collection, $model);
        }

        foreach ($model as $rowKey => $row) {
            $preProcessed = [];
            if ($this->preProcessJson) {
                $jsonKey = $this->preProcessJson;
                $preProcessed = json_decode($row->$jsonKey, true);
            }

            /**
             * If extra columns
             */
            if (! empty($prependColumns)) {
                $this->extraColumns($prependColumns, $row, $preProcessed);
            }

            foreach ($collection as $input) {
                $recipe = $input->getRecipe(static::class);
                $name = $recipe->name ?? $input->getName();

                $this->processRow($name, $input, $rowKey, $row, $recipe, $preProcessed);
            }

            /**
             * If extra columns
             */
            if (! empty($appendColumns)) {
                $this->extraColumns($appendColumns, $row, $preProcessed);
            }

            /**
             * Options
             */
            if (is_array($options) && ! empty($options)) {
                
                $allOptions = $this->options($row, $options);
                $optKey = 'options_table';

                foreach ($allOptions as $key => $optionGroup) {
                    $this->header[$optKey.'_'.$key] = $this->optionHeads[$key] ?? 'Options';
                    $this->body[$optKey.'_'.$key] = $optionGroup;
                }
            }

            /**
             * Last columns
             */
            if (! empty($lastColumns)) {
                $this->extraColumns($lastColumns, $row, $preProcessed);
            }

            $body[$rowKey] = $this->resolveContainer($this->body);
        }

        $this->output = new DataContainer([
            'head' => $this->resolveContainer($this->header),
            'body' => $body,
            'pagination' => $pagination,
            'count' => $count,
            'prepend' => $prependContent,
            'append' => $appendContent,

        ]);

        return $this->output;
    }

    protected function processRow($name, $input, $rowKey, $row, $recipe, array $preProcessed = [])
    {
        if (CrudAssistant::isInputCollection($input) && $this->controlsRecursion()) {
            foreach ($input as $subInput) {
                $subRecipe = $subInput->getRecipe(static::class);
                $subName = $subRecipe->name ?? $subInput->getName();
                $this->processRow($subName, $subInput, $rowKey, $row, $subRecipe, $preProcessed);
            }

            if (! $this->processInternalCollection()) {
                return true;
            }
        }

        $label = $input->getLabel();
        $inputHeader = $recipe->header ?? null;
        $callback = $recipe->callback ?? null;
        $ignoreIfEmpty = $recipe->ignoreIfEmpty ?? false;

        $value = $row->$name;

        if ($recipe && $recipe->isIgnored()) {
            return false;
        }

        if ($ignoreIfEmpty && $this->isEmpty($value)) {
            return false;
        }

        if ($rowKey == 0) {
            $label = $input->getLabel();
            $inputHeader = $recipe->header ?? null;
            $this->header[$name] = $inputHeader ?? $label;
        }

        $valueIfEmpty = $recipe->valueIfEmpty ?? null;
        if ($valueIfEmpty && $this->isEmpty($value)) {
            $this->body[$name] = $valueIfEmpty;

            return true;
        }

        if ($recipe) {
            /**
             * If callable
             */
            if ($callback && is_callable($callback)) {
                $value = $callback($input, $row, $preProcessed);
            }

            /**
             * Modifiers
             */
            $value = $this->modifiers($value, $input, $row);
        }

        return $this->body[$name] = $value;
    }

    public function extraColumns($extraColumns, $row, array $preProcessed = [])
    {
        foreach ($extraColumns as $key => $extraColumn) {
            $extraHeader = $extraColumn->header ?? null;
            $valueName = $extraColumn->value;
            $modifiers = $extraColumn->modifiers ?? [];

            if (! $extraHeader) {
                throw new InvalidArgumentException('An Extra Column header is empty', 500);
            }

            if (is_closure($valueName)) {
                $extraValue = $valueName($row, $preProcessed);
            } else {
                $extraValue = $row->$valueName;
            }

            /**`
             * Modifiers
             */
            foreach ($modifiers as $modifier) {
                $extraValue = $this->executeModifier($modifier, $extraValue, $row);
            }

            $this->header[$key] = $extraHeader;
            $this->body[$key] = $extraValue;
        }
    }

    public function options($row, $options)
    {
        $allOptions = [];
        foreach ($options as $key => $value) {
            $optionsAr = [];
            foreach ($value as $option) {
                $optionsAr[] = $option->create($row, $option->getData());
            }
            $allOptions[$key] = $optionsAr;
        }

        return $allOptions;
    }

    protected function resolveContainer($value)
    {
        if($this->arrayContainer && is_array($value)) {
            return $value;
        }

        if($this->arrayContainer) {
            return [$value];
        }
        
        return new ComponentTemplate($value);
        
        
    }

}
