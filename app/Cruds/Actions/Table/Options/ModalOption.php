<?php

namespace App\Cruds\Actions\Table\Options;

use App\Cruds\Views\EmptyView;
use App\Cruds\Views\ComponentTemplate;
use Chatagency\CrudAssistant\DataContainer;

class ModalOption extends Option
{
    public function create($content, DataContainer $data = null)
    {
        $data = $data ?? $this->getData();

        $disable = $data->disable ?? null;

        if (is_closure($disable) && $disable($content, $data)) {
            return EmptyView::make();
        }

        $id = $data->id ?? 'modal_option_'.$content->id;

        if (is_closure($id)) {
            $id = $id($content, $data);
        }

        $value = $data->value ?? null;

        if (is_closure($value)) {
            $value = $value($content, $data);
        }

        $button = $data->button ?? null;

        if (is_closure($button)) {
            $button = $button($content, $data);
        }

        $title = $data->title ?? null;

        if (is_closure($title)) {
            $title = $title($content, $data);
        }

        $footer = $data->footer ?? null;

        if (is_closure($footer)) {
            $footer = $footer($content, $data);
        }

        return ComponentTemplate::make([
            'id' => $id,
            'button' => $button,
            'title' => $title,
            'value' => $value,
            'footer' => $footer,
            'attributes' => $data->attributes ?? [],
        ])->setType('modal');
    }
}
