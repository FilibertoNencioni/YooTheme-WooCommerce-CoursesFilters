<?php

if (!$props['date']) {
    return;
}

// date
$el = $this->el('div', [

    'class' => [
        'el-date',
        'uk-text-{date_style: meta|lead}',
        'uk-{date_style: h1|h2|h3|h4|h5|h6} uk-margin-remove',
        'uk-text-{date_color}',
    ],

]);

echo $el($element, $props['date']);
