<?php

namespace App\Cruds\Actions\Table\Options;

use App\Cruds\Views\EmptyView;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\DataContainer;
use InvalidArgumentException;

/**
 * Link Option
 */
class LinkOption extends Option
{
    public function create($content, DataContainer $data = null)
    {
        $data = $data ?? $this->getData();
        $disable = $data->disable ?? null;

        if (is_closure($disable) && $disable($content, $data)) {
            return EmptyView::make();
        }

        $label = $data->label ?? $data->value ?? null;
        $attributes = $data->attributes ?? [];
        $theme = $data->theme ?? [];

        $href = $attributes['href'] ?? $data->action ?? null;

        if (is_closure($href)) {
            $href = $href($content, $data);
        }

        if (! $href) {
            throw new InvalidArgumentException('The href is not provided', 500);
        }

        $attributes['href'] = $href;

        if (is_closure($label)) {
            $label = $label($content, $data);
        }

        if (is_closure($attributes)) {
            $attributes = $attributes($content, $data);
        }

        if (is_closure($theme)) {
            $theme = $theme($content, $data);
        }

        return ComponentTemplate::make([
            'action' => $href,
            'value' => $label,
            'attributes' => $attributes,
            'extra' => $data->extra ?? [],
            'theme' => $theme,
        ])->settype('link');
    }
}
