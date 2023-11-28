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
        <p><?= $this->e($foo) ?></p>
    </div>

    <div>
        <?=$this->insert('components/form', [
            'inputs' => $inputs ?? null,
            'attributes' => [
                'method' => 'post',
                'action' => '/',
            ],
        ])?>
    </div>
    
</section>