<?php

namespace App\Cruds\Actions\Table\Options;

use App\Cruds\Actions\Form\InputPresenterAction;
use App\Cruds\Views\EmptyView;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\CrudAssistant;
use Chatagency\CrudAssistant\DataContainer;
use InvalidArgumentException;

/**
 * Form Option
 */
class FormOption extends Option
{
    public function create($content, DataContainer $data = null)
    {
        $data = $data ?? $this->getData();

        $disable = $data->disable ?? null;

        if (is_closure($disable) && $disable($content, $data)) {
            return EmptyView::make();
        }

        $action = $data->action ?? null;
        $value = $data->value ?? $data->label ?? null;
        $attributes = $data->attributes ?? [];
        $fields = $data->fields ?? null;
        $button = $data->button ?? null;

        if (! $action) {
            throw new InvalidArgumentException('The argument action is missing from the DataContainer class', 500);
        }

        if (is_closure($action)) {
            $action = $action($content, $data);
        }

        if (is_closure($value)) {
            $value = $value($content, $data);
        }

        if (is_closure($attributes)) {
            $attributes = $attributes($content, $data);
        }

        if (! is_array($attributes)) {
            throw new InvalidArgumentException('The attributes must be an array or a closure that returns an array', 500);
        }

        if (is_closure($button)) {
            $button = $button($content, $data);
        }

        if ($fields) {
            $fields = $this->fields($fields, $content, $data);
        }

        $attributes['action'] = $action;

        return ComponentTemplate::make([
            'action' => $action,
            'value' => $value,
            'inline' => $data->inline ?? false,
            'method' => $data->method ?? 'post',
            'button' => $button,
            'attributes' => $attributes,
            'extra' => $data->extra ?? [],
            'theme' => $data->theme ?? [],
            'fields' => $fields,
        ])->settype('form');
    }

    public function fields(array $fieldDefinitions, $model, $data)
    {
        $fields = [];
        $values = [];

        foreach ($fieldDefinitions as $key => $fieldDefinition) {
            $field = $fieldDefinition->field ?? null;
            $value = $fieldDefinition->value ?? null;

            if (is_closure($field)) {
                $fields[$key] = $field($model, $data);
            }

            if (is_closure($value)) {
                $values[$key] = $value($model, $data);
            }
        }

        return CrudAssistant::make($fields)
            ->execute(
                InputPresenterAction::make()
                    ->setGenericData(
                        new DataContainer([
                            'values' => $values,
                        ])
                    )
            );
    }
}
