<?php

return [
    // Base URL of Imagor server, as seen from the outside world
    'public_base_url' => env('IMAGOR_PUBLIC_BASE_URL', env('IMAGOR_BASE_URL')),
    // Base URL of Imagor server, as seen from the Laravel system (i.e. container2container f.e.)
    'internal_base_url' => env('IMAGOR_INTERNAL_BASE_URL', env('IMAGOR_BASE_URL')),
    // HMAC signature configuration
    // Secret used for signing URLs. If null, ImagorPathBuilder will output 'unsafe'
    'secret' => env('IMAGOR_SECRET'),
    // Hash algorithm used for signature, e.g. sha256
    'signer_type' => env('IMAGOR_SIGNER_TYPE', 'sha256'),
    // Truncate signed token to this length; null to keep full length
    'signer_truncate' => env('IMAGOR_SIGNER_TRUNCATE'),

    'path_map' => [
        // the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix
        storage_path() => '/storage',
    ]
];
