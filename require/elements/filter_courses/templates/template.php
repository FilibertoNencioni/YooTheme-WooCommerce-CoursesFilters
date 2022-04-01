<?php
namespace YOOtheme;
require_once'filter.php';

$attributes = array(
    'Tecnologie'=>array(),
    'Ruolo'=>array(),
    'Vendor'=>array(),
    'Modalita di erogazione'=>array(),
    'Durata corso'=>array(),
    'Calendario'=>array(),
    'Sede'=>array(),
    'Status'=>array()
);

$text_fields = ['title', 'site', 'date', 'price'];

switch ($props['table_order']) {
    case 1:
        $fields = ['image', 'title', 'site', 'date', 'price', 'link'];
        break;
    case 2:
        $fields = ['image', 'title', 'date', 'site', 'price', 'link'];
        break;
}

// Find empty fields
$filtered = array_values(Arr::filter($fields, function ($field) use ($props, $children) {
    return $props["show_{$field}"] && Arr::some($children, function ($child) use ($field) {
        return $child->props[$field];
    });
}));

$el = $this->el('div', [

    // Responsive
    'class' => [
        'uk-overflow-auto {@table_responsive: overflow}',
    ],

]);

$table = $this->el('table', [

    'class' => [

        // Style
        'uk-table',
        'uk-table-{table_style}',
        'uk-table-hover {@table_hover}',
        'uk-table-justify {@table_justify}',

        // Size
        'uk-table-{table_size}',

        // Vertical align
        'uk-table-middle {@table_vertical_align}',

        // Responsive
        'uk-table-responsive {@table_responsive: responsive}',
    ],

]);

?>

<?php if ($props['table_responsive'] == 'overflow') : ?>
<?= $el($props, $attrs) ?>
    <?= $table($props) ?>
<?php else : ?>
    <?= $table($props, $attrs) ?>
<?php endif ?>

        <?php if (Arr::some($filtered, function ($field) use ($props) { return $props["table_head_{$field}"]; })) : ?>
        <thead>
            <tr>

                <?php foreach ($filtered as $i => $field) {

                    $lastColumn = $i !== 0 && !isset($filtered[$i + 1]);

                    echo $this->el('th', [

                        'class' => [
                            // Last column alignment
                            'uk-text-{table_last_align}[@m {@table_responsive: responsive}]' => $lastColumn,

                            // Text align need to be set for table heading
                            'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}] {@!text_align: justify}]' => !$lastColumn || !$props['table_last_align'],

                            // Text nowrap
                            'uk-text-nowrap' => $field == 'link' || in_array($field, $text_fields) && $props["table_width_{$field}"] == 'shrink',
                        ],

                    ], $props["table_head_{$field}"])->render($props);

                } ?>

            </tr>
        </thead>
        <?php endif ?>

        <tbody>

        
        <?php if ($props['enable_filters'] == true):?>
            
            <?php 
                foreach ($children as $i => $child) : ?>
                <?php 
                    if(strlen($child->props['attributes'])>1){
                        //GET ALL ATTRIBUTES           
                        $attributes = getData($child->props['attributes'], $attributes);

                        //GET ATTRIBUTE OF THE CHILD PRODUCT
                        $singleAttributes = getSingleData($child->props['attributes']);
                    }
                    $link = $child->props['link'];

                ?>
                <?php if(!Str::length($link)) : ?>
                    <tr class="el-item" tag-tecnologie="<?= $singleAttributes['Tecnologie'][0]?>" tag-ruolo="<?= $singleAttributes['Ruolo'][0]?>" tag-vendor="<?= $singleAttributes['Vendor'][0]?>" tag-erogazione="<?= $singleAttributes['Modalita di erogazione'][0]?>" tag-durata="<?= $singleAttributes['Durata corso'][0]?>" tag-calendario="<?= $singleAttributes['Calendario'][0]?>" tag-sede="<?= $singleAttributes['Sede'][0]?>" tag-status="<?= $singleAttributes['Status'][0]?>"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                <?php else : ?>
                    <tr class="el-item" tag-tecnologie="<?= $singleAttributes['Tecnologie'][0]?>" tag-ruolo="<?= $singleAttributes['Ruolo'][0]?>" tag-vendor="<?= $singleAttributes['Vendor'][0]?>" tag-erogazione="<?= $singleAttributes['Modalita di erogazione'][0]?>" tag-durata="<?= $singleAttributes['Durata corso'][0]?>" tag-calendario="<?= $singleAttributes['Calendario'][0]?>" tag-sede="<?= $singleAttributes['Sede'][0]?>" tag-status="<?= $singleAttributes['Status'][0]?>" style="cursor:pointer;" onclick="window.location='<?= $link ?>'"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
                <?php endif ?>
            <?php endforeach ?>
            <?php 
            // var_dump($attributes);
            ?>

        <?php endif ?>

        <?php if ($props['enable_filters'] != true):?>
            <?php foreach ($children as $i => $child) : ?>
                <tr class="el-item"><?= $builder->render($child, ['i' => $i, 'element' => $props, 'fields' => $fields, 'text_fields' => $text_fields, 'filtered' => $filtered]) ?></tr>
            <?php endforeach ?>
        <?php endif ?>


        </tbody>

    </table>

<?php if ($props['table_responsive'] == 'overflow') : ?>
</div>
<?php endif ?>

<?php if ($props['enable_filters'] == true){
    // var_dump($child->props['attributes']);
    // $products = wc_get_products( array( 'status' => 'publish', 'limit' => -1 ) );
    // var_dump($product);
}

?>
