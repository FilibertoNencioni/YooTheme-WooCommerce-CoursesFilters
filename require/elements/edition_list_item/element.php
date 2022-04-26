<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return Str::length($node->props['date']) ||
                $node->props['site'];
        },
    ],
];
