<?php


// Content -> date and/or site
$content = $this->el('div', [

    'class' => [
        'el-content'
    ],

]);


?>

<?php if ($props['date'] && !$props['site']) : ?>
    <div> <?= $content($element, $props['date']) ?> </div>
<?php elseif(!$props['date'] && $props['site']) : ?>
    <div> <?= $content($element, $props['site']) ?></div>
<?php elseif($props['date'] && $props['site']) : ?>
    <div> <?= $content($element, $props['date']." - ".$props['site']) ?> </div>
<?php endif ?>
