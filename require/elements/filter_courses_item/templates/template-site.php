<?php

namespace YOOtheme;

if (!Str::length($props['site'])) {
    return;
}

// Site
$el = $this->el('div', [

    'class' => [
        'el-site uk-panel',
        'uk-text-{site_style: meta|lead}',
        'uk-{site_style: h1|h2|h3|h4|h5|h6} uk-margin-remove',
    ],

]);

echo $el($element, $props['site']);
