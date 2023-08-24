<?php 
    $id = $input['id'] ?? $input['name'];
    $type = $input['type'];
    $name = $input['name'];
?>

<input 
    type="<?=$type?>"
    name="<?=$name?>"
    id="<?=$id?>"/>