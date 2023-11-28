<?php

/** @var \League\Plates\Template\Template $this */ 

$this->insert('components/inputs/label', [
    'input' => $input->toArray()
]);

$this->insert('components/inputs/'.$input->type, [
    'input' => $input->toArray()
]);