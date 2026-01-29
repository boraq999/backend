<?php

return [
    'mode' => env('DOMPDF_MODE', 'utf-8'),
    'defines' => [
        'DOMPDF_TEMP_DIR' => storage_path('app'),
        'DOMPDF_CHROOT' => public_path(),
        'DOMPDF_ENABLE_AUTOLOAD' => false,
    ],
    'public_path' => null,
    'convert_entities' => true,
    'options' => [
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => false,
        'defaultFont' => 'DejaVu Sans',
    ],
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
];
