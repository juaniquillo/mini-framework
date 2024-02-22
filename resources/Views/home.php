<?php /** @var \League\Plates\Template\Template $this */ ?>

<?php $this->layout('layouts/template', [
    'title' => $title ?? null
]) ?>

<?=$this->insert('partials/header', [
    'title' => $title ?? null
])?>

<section>
    
    <h2>Welcome</h2>


    <div>
        This is a view
    </div>
    
</section>