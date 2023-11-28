<?php /** @var \League\Plates\Template\Template $this */ ?>

<?php 
    $attributes = $input['attributes'] ?? [];
    
    $attributes['for'] = $attributes['for'] ?? $input['name'];
?>
<label <?= inlineAttrs($attributes) ?>>
<?=$input['label'] ?>
</label>