<?php

declare(strict_types=1);

return [

    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS'),
    ],

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],

    'dynamic_links' => [
        'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
    ],

    'storage' => [
        'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
    ],

    'auth' => [
        'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],

    // fallback jika auth config gagal
    'project_id' => env('FIREBASE_PROJECT_ID'),
];
