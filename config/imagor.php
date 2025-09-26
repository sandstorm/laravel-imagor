<?php

return [
    'base_url' => env('IMAGOR_BASE_URL'),
    // HMAC signature configuration
    // Secret used for signing URLs. If null, ImagorPathBuilder will output 'unsafe'
    'secret' => env('IMAGOR_SECRET'),
    // Hash algorithm used for signature, e.g. sha256
    'signer_type' => env('IMAGOR_SIGNER_TYPE', 'sha256'),
    // Truncate signed token to this length; null to keep full length
    'signer_truncate' => env('IMAGOR_SIGNER_TRUNCATE'),
];
