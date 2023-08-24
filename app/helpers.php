<?php

if (! function_exists('is_closure')) {
    function is_closure($t)
    {
        return $t instanceof \Closure;
    }
}

if (! function_exists('dashes_to_camel_case')) {
    function dashes_to_camel_case($string, $capitalizeFirstCharacter = false) 
    {

        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}