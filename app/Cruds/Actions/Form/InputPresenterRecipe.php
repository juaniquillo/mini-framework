<?php

namespace App\Cruds\Actions\Form;

use App\Cruds\Actions\Form\InputPresenterAction;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\Contracts\RecipeInterface;
use Chatagency\CrudAssistant\RecipeBase;
use Closure;

class InputPresenterRecipe extends RecipeBase implements RecipeInterface
{
    public string $title;

    /**
     * Undocumented variable
     *
     * @var string|ComponentTemplate
     */
    public $helpText;

    public string $invalidFeedback;

    public array $labelAttributes;

    public array $extra;

    /**
     * @var string|Closure
     */
    public $theme;

    public $label;

    public $prepend;

    public $append;

    /**
     * @var string|Closure
     */
    public $value;

    public array $accessors;

    /**
     * Recipe action
     *
     * @var string
     */
    protected $action = InputPresenterAction::class;
}
