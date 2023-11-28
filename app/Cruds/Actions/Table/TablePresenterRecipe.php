<?php

namespace App\Cruds\Actions\Table;

use App\Cruds\Actions\Table\TablePresenterAction;
use Chatagency\CrudAssistant\Contracts\RecipeInterface;
use Chatagency\CrudAssistant\RecipeBase;
use Closure;

class TablePresenterRecipe extends RecipeBase implements RecipeInterface
{
    /**
     * Overwrites input name
     */
    public string $name;

    /**
     * Overwrites table header
     */
    public string $header;

    /**
     * Callback that overwrites value
     */
    public Closure $callback;

    /**
     * Ignores input if the value is empty
     */
    public bool $ignoreIfEmpty = false;

    /**
     * Overwrites value if empty
     */
    public string $valueIfEmpty;

    /**
     * Recipe action
     *
     * @var string
     */
    protected $action = TablePresenterAction::class;
}
