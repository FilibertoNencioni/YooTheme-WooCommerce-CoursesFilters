<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return Str::length($node->props['title']) ||
                Str::lenght($node->props['site']) ||
                Str::lenght($node->props['date']) ||
                Str::lenght($node->props['price']) ||
                $node->props['image'];
        },
    ],
];
