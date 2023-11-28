<?php

namespace App\Cruds\Actions\Form;

use Chatagency\CrudAssistant\Action;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\CrudAssistant;
use Chatagency\CrudAssistant\DataContainer;
use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\Contracts\ActionInterface;
use App\Cruds\Actions\Form\Accessors\AccessorInterface;
use App\Cruds\Actions\Form\Accessors\DefaultErrorAccessor;
use App\Cruds\Actions\Form\Accessors\DefaultValueAccessor;
use Chatagency\CrudAssistant\Contracts\DataContainerInterface;

/**
 * Input presenter action.
 */
class InputPresenterAction extends Action implements ActionInterface
{
    /**
     * Controls recursion
     *
     * @var bool
     */
    protected $controlsRecursion = true;

    /**
     * value
     *
     * @var AccessorInterface
     */
    protected $valueAccessor;

    /**
     * Error accessor
     *
     * @var AccessorInterface
     */
    protected $errorAccessor;

    /**
     * Parent input
     *
     * @var DataContainer
     */
    protected $parent;

    /**
     * Input values
     * 
     * @var array
     */
    protected $values = [];

    protected $count = 0;

    /**
     * Construct.
     *
     * @param  DataContainerInterface  $output
     */
    public function __construct(DataContainerInterface $output = null)
    {
        $this->setValueAccessor(new DefaultValueAccessor);
        $this->setErrorAccessor(new DefaultErrorAccessor);

        return parent::__construct($output);
    }

    /**
     * Disable is tree
     *
     * @return self
     */
    public function isFlat()
    {
        $this->controlsRecursion = false;

        return $this;
    }

    /**
     * Pre Execution.
     *
     * @return self
     */
    public function prepare()
    {
        $this->output->inputs = [];
        $this->output->hidden = [];

        return $this;
    }

    /**
     * Execute action on input.
     *
     * @return DataContainerInterface
     */
    public function execute(InputInterface $input)
    {
        $output = $this->output;
        $params = $this->getGenericData();

        $inputPresenters = $output->inputs;
        $hiddenPresenters = $output->hidden;

        $name = $input->getName();
        $type = $input->getType();

        $data = $this->populateContainer($input, $params);

        if (! $data) {
            return $output;
        }

        if ($type === 'hidden') {
            $hiddenPresenters[$name] = $data;
        } else {
            $inputPresenters[$name] = $data;
        }

        $output->inputs = $inputPresenters;
        $output->hidden = $hiddenPresenters;
        $output->values = $this->getValues();

        return $output;
    }

    public function getValues()
    {
        return $this->values;
    }

    /**
     * Sets value accessor
     *
     * @param  AccessorInterface  $valueAccessor
     * @return self
     */
    public function setValueAccessor(AccessorInterface $valueAccessor)
    {
        $this->valueAccessor = $valueAccessor;

        return $this;
    }

    /**
     * Sets error accessor
     *
     * @param  AccessorInterface  $errorAccessor
     * @return self
     */
    public function setErrorAccessor(AccessorInterface $errorAccessor)
    {
        $this->errorAccessor = $errorAccessor;

        return $this;
    }

    public function populateContainer(InputInterface $input, DataContainer $params = null)
    {
        $recipe = $input->getRecipe($this->getIdentifier());
        $params = $params ?? DataContainer::make();

        $data = $this->createContainer($input, $params);

        $disableValue = $recipe->disableValue ?? false;
        if(!$disableValue) {
            $value = $this->values[$data->name] = $this->getValue($input, $params, $data);
            $data->value = $this->modifiers($value, $input);
        }
        
        
        $data->error = $this->getError($input, $params, $data);
        $data->inputs = null;

        $data = $this->extraContent($data, $input, $params);

        /**
         * Internal collection
         */
        if (CrudAssistant::isInputCollection($input) && $this->controlsRecursion()) {
            
            $inputName = $input->getName();
            $model = $params->model ?? null;

            $subParams = new DataContainer([
                'values' => isset($params->values) ? $params->values[$inputName] ?? [] : [],
                'errors' => isset($params->errors) ? $params->errors[$inputName] ?? [] : [],
                'parentValues' => $params->values ?? [],
                'model' => $model->$inputName ?? $model,
                'theme' => $params->theme ?? [],
                'extra' => $params->extra ?? [],
            ]);

            $subAction = static::make()
                ->setGenericData($params ?? $subParams)
                ->setValueAccessor($this->valueAccessor)
                ->setErrorAccessor($this->errorAccessor)
                ->setParent(new DataContainer([
                    'name' => $data->name,
                    'label' => $data->label,
                    'value' => $data->value ?? null,
                    'error' => $data->error,
                    'theme' => $data->theme,
                    'extra' => $data->extra,
                ]))
                ->prepare();

            foreach ($input as $subInput) {
                $subAction->execute($subInput);
            }   
            
            $this->values = array_merge($this->values, $subAction->getValues());
            
            $data->inputs = $subAction->getOutput()->inputs;
            
        }

        /**
         * Sub Elements
         */
        $subElements = $input->getSubElements();

        if ($subElements) {
            $subAction = static::make()
                ->setGenericData($params ?? new DataContainer())
                ->setValueAccessor($this->valueAccessor)
                ->setErrorAccessor($this->errorAccessor)
                ->setParent(new DataContainer([
                    'name' => $data->name,
                    'label' => $data->label,
                    'value' => $data->value ?? null,
                    'error' => $data->error,
                    'theme' => $data->theme,
                    'extra' => $data->extra,
                ]))
                ->prepare();

            foreach ($input->getSubElements() as $subInput) {
                $subAction->execute($subInput);
            }

            $data->subElements = $subAction->getOutput()->inputs;
        }

        return $data;
    }

    protected function createContainer(InputInterface $input, DataContainer $params)
    {
        $data = new ComponentTemplate();
        $recipe = $input->getRecipe($this->getIdentifier());

        $data->name = $input->getName();
        $data->label = isset($recipe->label) ? $recipe->label : $input->getLabel();

        $type = $input->getType();
        $data->type = $type;
        
        /**
         * Set component template type
         */
        $data->setType($data->type);

        $data->title = $recipe && isset($recipe->title) ? $recipe->title : null;
        $data->helpText = $recipe && isset($recipe->helpText) ? $recipe->helpText : null;
        $data->invalidFeedback = $recipe && isset($recipe->invalidFeedback) ? $recipe->invalidFeedback : null;
        $data->labelAttributes = $recipe && isset($recipe->labelAttributes) ? $recipe->labelAttributes : [];
        $data->extra = $recipe->extra ?? $params->extra ?? [];
        $data->theme = $recipe->theme ?? $params->theme ?? [];

        if (is_closure($data->theme)) {
            $fn = $data->theme;
            $data->theme = $fn($input, $params);
        }

        $data->attributes = $input->getAttributes() ?? [];

        $data->parent = $this->getParent() ?? null;

        return $data;
    }

    public function extraContent(DataContainer $data, InputInterface $input, DataContainer $params)
    {
        $extra = $data->extra;
        
        $recipe = $input->getRecipe($this->getIdentifier());

        /**
         * Prepend
         */
        $prepend = $recipe->prepend ?? $params->prepend ?? null;

        if($prepend ) {
            if (is_closure($prepend)) {
                $prepend = $prepend($input, $params);
            }
            $extra['prepend'] = $prepend;
        }

        /**
         * Append
         */
        $append = $recipe->append ?? $params->append ?? ($input->getType() == 'file' ? $data->value : null) ?? null;
        
        if($append ) {
            if (is_closure($append)) {
                $append = $append($input, $params);
            }
            $extra['append'] = $append;
        }
        
        $data->extra = $extra;

        return $data;
    }

    /**
     * Set parent input
     *
     * @param  DataContainer  $parent  Parent input
     * @return  self
     */
    protected function setParent(DataContainer $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    protected function getParent()
    {
        return $this->parent;
    }

    protected function getValue(InputInterface $input, $params, $data = null)
    {
        return $this->valueAccessor->get(
            $input,
            $params,
            $input->getRecipe(static::class),
            $data
        );
    }

    protected function getError(InputInterface $input, $params, $data = null)
    {
        return $this->errorAccessor->get(
            $input,
            $params,
            $input->getRecipe(static::class),
            $data
        );
    }

}
