<?php 

return [
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_santized'   => env('MIDTRANS_IS_SANITIZED', true),
]