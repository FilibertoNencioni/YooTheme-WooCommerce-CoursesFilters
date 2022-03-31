<?php

if (!$props['price']) {
    return;
}

// price
$el = $this->el('div', [

    'class' => [
        'el-price',
        'uk-text-{price_style: price|lead}',
        'uk-{price_style: h1|h2|h3|h4|h5|h6} uk-margin-remove',
        'uk-text-{price_color}',
    ],

]);

echo $el($element, $props['price']);
