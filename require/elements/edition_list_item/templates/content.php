<?php if ($props['date'] && !$props['site']) : ?>
    <div><?= $props['date'] ?></div>
<?php elseif(!$props['date'] && $props['site']) : ?>
    <div> <?= $props['site'] ?></div>
<?php elseif($props['date'] && $props['site']) : ?>
    <div><?= $props['date'] ?> - <?= $props['site'] ?></div>
<?php endif ?>
