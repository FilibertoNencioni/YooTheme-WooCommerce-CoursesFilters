<?php if (in_array('image', $filtered)) : ?>
<td>
    <?php if ($props['image']) : ?>
    <img src="<?= $props['image'] ?>" alt="<?= $props['image_alt'] ?>">
    <?php endif ?>
</td>
<?php endif ?>

<?php if (in_array('title', $filtered)) : ?>
<td><?= $props['title'] ?> </td>
<?php endif ?>

<?php if (in_array('site', $filtered)) : ?>
<td><?= $props['site'] ?></td>
<?php endif ?>

<?php if (in_array('date', $filtered)) : ?>
<td><?= $props['date'] ?></td>
<?php endif ?>

<?php if (in_array('price', $filtered)) : ?>
<td><?= $props['price'] ?></td>
<?php endif ?>


<?php if (in_array('link', $filtered)) : ?>
<td>
    <?php if ($props['link']) : ?>
    <a href="<?= $props['link'] ?>"><?= $props['link_text'] ?: $element['link_text'] ?></a>
    <?php endif ?>
</td>
<?php endif ?>
