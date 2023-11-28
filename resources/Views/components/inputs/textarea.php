<?php /** @var \League\Plates\Template\Template $this */ ?>

<?php 
    $attributes = $input['attributes'] ?? [];

    $attributes['id']= $attributes['id'] ?? $input['name'];
    $attributes['name'] = $attributes['name'] ?? $input['name'];

    $value = $attributes['value'] ?? null;
?>

<textarea <?= inlineAttrs($attributes) ?>><?= $value ?></textarea>