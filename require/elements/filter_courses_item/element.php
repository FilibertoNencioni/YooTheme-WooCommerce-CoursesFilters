<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            // Don't render element if content fields are empty
            return Str::length($node->props['title']) ||
                Str::length($node->props['site']) ||
                Str::length($node->props['date']) ||
                Str::length($node->props['price']) ||
                $node->props['image'];
        },
    ],
];
