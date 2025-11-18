<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Jam kerja standar
    |--------------------------------------------------------------------------
    |
    | Nilai default mengikuti environment variable untuk menjaga kompatibilitas
    | dengan backend HadirIn. Semua nilai dapat ditimpa lewat .env.
    |
    */

    'work_start' => env('WORK_START', '10:00'),
    'work_end' => env('WORK_END', '16:00'),
    'grace_minutes' => env('GRACE_MINUTES', 0),

    /*
    |--------------------------------------------------------------------------
    | Endpoint face service (FastAPI)
    |--------------------------------------------------------------------------
    */
    'fastapi_url' => env('FASTAPI_URL', ''),
];

