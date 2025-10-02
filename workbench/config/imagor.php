<?php

return [
    'public_base_url' => env('IMAGOR_PUBLIC_BASE_URL', env('IMAGOR_BASE_URL', 'http://localhost:8001/')),
    'internal_base_url' => env('IMAGOR_INTERNAL_BASE_URL', env('IMAGOR_BASE_URL', 'http://localhost:8001/')),

    // HMAC signature configuration
    // Secret used for signing URLs. If null, ImagorPathBuilder will output 'unsafe'
    'secret' => env('IMAGOR_SECRET', 'UNSAFE_DEV_SECRET'),
    // Hash algorithm used for signature, e.g. sha256
    'signer_type' => env('IMAGOR_SIGNER_TYPE', 'sha256'),
    // Truncate signed token to this length; null to keep full length
    'signer_truncate' => env('IMAGOR_SIGNER_TRUNCATE', '40'),
];
