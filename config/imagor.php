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

    // URL encoding mode for image source paths. Determines how image URLs are encoded before being sent to Imagor.
    // Base64 variants are more robust when using reverse proxies and having Umlauts; but needs https://github.com/cshum/imagor/pull/624 to be merged.
    //
    // Options:
    // - 'none' (no encoding, potentially unstable),
    // - 'urlencode' (standard URL encoding, required for older Imagor versions)
    // - 'base64_if_unsafe_chars' (Base64 encode only if path contains characters outside [a-zA-Z0-9-_./])
    // - 'base64_if_unsafe_chars_conservative' (Base64 encode if path contains characters outside [a-zA-Z0-9-_.], excludes /)
    // - 'base64' (always Base64 encode)

    'url_encode_mode' => env('IMAGOR_URL_ENCODE_MODE', 'urlencode'),

    'path_map' => [
        // the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix
        storage_path() => '/storage',
    ]
];
