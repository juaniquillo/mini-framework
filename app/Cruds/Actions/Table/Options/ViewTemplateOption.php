<?php

namespace App\Cruds\Actions\Table\Options;

use App\Cruds\Views\EmptyView;
use Chatagency\CrudAssistant\DataContainer;
use Exception;

class ViewTemplateOption extends Option
{
    public function create($content, DataContainer $data = null)
    {
        $data = $data ?? $this->getData();
        $callback = $data->callback ?? null;

        if (is_closure($callback)) {
            $view = $callback($content, $data);
            if (! isViewTemplate($view)) {
                throw new Exception('The result of the callback is not a view', 500);
            }

            return $view;
        }

        return EmptyView::make();
    }
}
