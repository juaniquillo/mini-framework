<?php /** @var \League\Plates\Template\Template $this */ ?>

<?php 
    
    $attributes = $input['attributes'] ?? [];
    
    $attributes['id']= $attributes['id'] ?? $input['name'];
    $attributes['type'] = $attributes['type'] ?? $input['type'];
    $attributes['name'] = $attributes['name'] ?? $input['name'];
    
    $attributes['value'] = $attributes['value'] ?? $input['value'];
    
    // dd($input);
?>

<input <?= inlineAttrs($attributes) ?>>