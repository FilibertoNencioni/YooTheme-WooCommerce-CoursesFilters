<?php

namespace YOOtheme;

return [
    'transforms' =>[
        'render' => function(){
            app(Metadata::class)->set('script:filter_courses_script', [
                'src' => Path::get('./app/filter_courses.js'),
                'defer' => true,
            ]);

            
        }
    ],
    'updates' => [
        '1.20.0-beta.1.1' => function ($node) {
            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }
        },

        '1.20.0-beta.0.1' => function ($node) {
            /**
             * @var Config $config
             */
            $config = app(Config::class);

            list($style) = explode(':', $config('~theme.style'));

            if (Arr::get($node->props, 'title_style') === 'heading-primary') {
                $node->props['title_style'] = 'heading-medium';
            }

            if (
                in_array($style, [
                    'craft',
                    'district',
                    'jack-backer',
                    'tomsen-brody',
                    'vision',
                    'florence',
                    'max',
                    'nioh-studio',
                    'sonic',
                    'summit',
                    'trek',
                ])
            ) {
                if (
                    Arr::get($node->props, 'title_style') === 'h1' ||
                    (empty($node->props['title_style']) &&
                        Arr::get($node->props, 'title_element') === 'h1')
                ) {
                    $node->props['title_style'] = 'heading-small';
                }
            }

            if (in_array($style, ['florence', 'max', 'nioh-studio', 'sonic', 'summit', 'trek'])) {
                if (Arr::get($node->props, 'title_style') === 'h2') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h1' ? '' : 'h1';
                } elseif (
                    empty($node->props['title_style']) &&
                    Arr::get($node->props, 'title_element') === 'h2'
                ) {
                    $node->props['title_style'] = 'h1';
                }
            }

            if (in_array($style, ['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] = 'heading-small';
                }
            }

            if (in_array($style, ['copper-hill'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h1' ? '' : 'h1';
                } elseif (Arr::get($node->props, 'title_style') === 'h1') {
                    $node->props['title_style'] =
                        Arr::get($node->props, 'title_element') === 'h2' ? '' : 'h2';
                } elseif (
                    empty($node->props['title_style']) &&
                    Arr::get($node->props, 'title_element') === 'h1'
                ) {
                    $node->props['title_style'] = 'h2';
                }
            }

            if (in_array($style, ['trek', 'fjord'])) {
                if (Arr::get($node->props, 'title_style') === 'heading-medium') {
                    $node->props['title_style'] = 'heading-large';
                }
            }
        },

        '1.18.10.1' => function ($node) {
            if (isset($node->props['image_inline_svg'])) {
                $node->props['image_svg_inline'] = $node->props['image_inline_svg'];
                unset($node->props['image_inline_svg']);
            }

            if (isset($node->props['image_animate_svg'])) {
                $node->props['image_svg_animate'] = $node->props['image_animate_svg'];
                unset($node->props['image_animate_svg']);
            }
        },

        '1.18.0' => function ($node) {
            if (
                !isset($node->props['site_color']) &&
                in_array(Arr::get($node->props, 'site_style'), ['muted', 'primary'], true)
            ) {
                $node->props['site_color'] = $node->props['site_style'];
                $node->props['site_style'] = '';
            }

            if (
                !isset($node->props['date_color']) &&
                in_array(Arr::get($node->props, 'date_style'), ['muted', 'primary'], true)
            ) {
                $node->props['date_color'] = $node->props['date_style'];
                $node->props['date_style'] = '';
            }

            if (
                !isset($node->props['price_color']) &&
                in_array(Arr::get($node->props, 'price_style'), ['muted', 'primary'], true)
            ) {
                $node->props['price_color'] = $node->props['price_style'];
                $node->props['price_style'] = '';
            }
        },
    ],
];
