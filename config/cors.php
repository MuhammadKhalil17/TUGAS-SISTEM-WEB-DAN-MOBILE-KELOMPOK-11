<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    // Mengizinkan CORS untuk semua rute API dan cookie Sanctum
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Menentukan HTTP metode apa saja yang diizinkan (GET, POST, PUT, DELETE, dll)
    'allowed_methods' => ['*'],

    // Mengizinkan request dari origin/domain mana pun 
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // Mengizinkan semua jenis custom Headers (seperti Authorization: Bearer, Accept, dll)
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Ubah menjadi true untuk jika nanti frontend ngirimkan cookie/kredensial sesi
    'supports_credentials' => true,

];