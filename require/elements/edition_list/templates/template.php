<?php

$el = $this->el("ul", [

    'class' => [
        'uk-list',
        'uk-margin-remove {position: absolute}',
    ],

]);

$item = $this->el('li', [

    'class' => [
        'el-item',
    ],

]);

?>

<?= $el($props, $attrs) ?>
<?php foreach ($children as $i => $child) : ?>

    <?= $item($props) ?>
        <?= $builder->render($child, ['element' => $props]) ?>
    <?= $item->end() ?>

<?php endforeach ?>
<?= $el->end() ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    console.log($(".product_title").text());
</script>
