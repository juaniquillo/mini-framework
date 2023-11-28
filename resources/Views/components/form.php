<?php /** @var \League\Plates\Template\Template $this */ ?>

<?php

$inputs = $inputs->inputs ?? [];

$attributes = $attributes ?? [];

?>

<form <?= inlineAttrs($attributes) ?>>
    <?php foreach ($inputs as $input) { 
        $this->insert('components/inputGroup', [
            'input' => $input
        ]);
    }?>

    <div>
        <button>Send</button>
    </div>
</form>