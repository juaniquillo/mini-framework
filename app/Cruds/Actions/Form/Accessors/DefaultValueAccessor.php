<?php

namespace App\Cruds\Actions\Form\Accessors;

use Chatagency\CrudAssistant\Contracts\InputInterface;
use Chatagency\CrudAssistant\DataContainer;

class DefaultValueAccessor implements AccessorInterface
{
    public function get(InputInterface $input, $params, $recipe = null, $data = null)
    {
        $inputName = $input->getName();

        if ($input->getType() == 'collection') {
            return;
        }

        $values = array_merge($params->parentValues ?? [], $params->values ?? []);
        $model = $params->model ?? [];

        /**
         * If is string assume it is json
         */
        if (is_string($values)) {
            $values = $this->jsonDecode($values);
        }

        if (array_key_exists($inputName, $values)) {
            return $values[$inputName];
        }

        /**
         * If is string assume it is json
         */
        if (is_string($model)) {
            $model = new DataContainer($this->jsonDecode($model));
        }

        if ($recipe && isset($recipe->value)) {
            $value = $recipe->value;
            if (is_callable($value)) {
                return $value($input, $params, $recipe, $data);
            }

            return $value;
        }

        if ($model && isset($model->$inputName)) {
            return $model->$inputName;
        }

        return null;
    }

    /**
     * Tries to json encode
     *
     * @param  string  $json
     * @return array
     */
    public function jsonDecode(string $json)
    {
        $values = json_decode($json, true);

        return $values ?? [];
    }
}
