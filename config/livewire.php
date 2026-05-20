<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'lazy_placeholder' => null,
    'temporary_file_upload' => [
        'disk'        => null,
        'rules'       => null,
        'directory'   => null,
        'middleware'  => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'rtf', 'pdf', 'psd', 'ai', 'jpg',
            'jpeg', 'webp',
        ],
        'max_upload_time' => 5,
        'cleanup'     => true,
    ],
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'inject_assets' => true,
    'inject_morph_map' => true,
    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],
    'pagination_theme' => 'tailwind',
    'middleware_group' => 'web',
    'lock_timeout' => 120,
    'html_return_from_view' => true,
    'asset_url' => null,
    'app_url' => null,
];
