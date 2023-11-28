<?php

namespace App\Cruds\Actions\Form\Accessors;

use Chatagency\CrudAssistant\Contracts\InputInterface;

class DefaultErrorAccessor implements AccessorInterface
{
    public function get(InputInterface $input, $params, $recipe = null, $data = null)
    {
        $inputName = $input->getName();
        $errors = $params->errors ?? [];

        if ($recipe && isset($recipe->error)) {
            $error = $recipe->error;
            if (is_closure($error)) {
                return $error($input, $params, $recipe, $data);
            }
        }

        /**
         * If is string assume it is json
         */
        if (is_string($errors)) {
            $errors = $this->jsonDecode($errors);
        }

        if (isset($errors[$inputName])) {
            return $errors[$inputName];
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
