<?php

namespace App\Cruds\Actions\Table\Options;

use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\DataContainer;

/**
 * Link Option
 */
class ButtonOption extends Option
{
    public function create($content, DataContainer $data = null)
    {
        $data = $data ?? $this->getData();
        $label = $data->label ?? null;
        $attributes = $data->attributes ?? [];

        if (is_callable($label)) {
            $label = $label($content, $data);
        }

        if (is_callable($attributes)) {
            $attributes = $attributes($content, $data);
        }

        $template = new ComponentTemplate([
            'value' => $label,
            'icon' => $data->icon ?? null,
            'class' => $data->class ?? null,
            'title' => $data->title ?? null,
            'extra' => $data->extra ?? [],
        ]);

        $template->setType('button')
            ->setAttributes($attributes);

        return $template;
    }
}
