<?php

namespace Sandstorm\LaravelImagor;

/**
 * Decide how URL encoding of image URLs should be done.
 *
 * @internal
 */
enum UrlEncodeMode: string
{
    // do not URLencode (! unsafe potentially!)
    case NONE = 'none';

    // URLencode as usual (required before https://github.com/cshum/imagor/pull/624 is merged)
    case URLENCODE = 'urlencode';

    // allows [a-zA-Z0-9-_./] -> including / character, otherwise URLencode
    case BASE64_IF_UNSAFE_CHARS = 'base64_if_unsafe_chars';

    // allows [a-zA-Z0-9-_.] -> does not allow / as it might lead to problems
    case BASE64_IF_UNSAFE_CHARS_CONSERVATIVE = 'base64_if_unsafe_chars_conservative';

    // always encode as base64
    case BASE64 = 'base64';

    public static function fromConfig(string|bool $config): self
    {
        return self::tryFrom($config) ?? self::URLENCODE;
    }

    public function encodeSourcePath(string $sourcePath): string
    {
        return match ($this) {
            self::NONE => $sourcePath,
            self::URLENCODE => self::urlencode($sourcePath),
            self::BASE64_IF_UNSAFE_CHARS => self::base64IfUnsafeChars($sourcePath),
            self::BASE64_IF_UNSAFE_CHARS_CONSERVATIVE => self::base64IfUnsafeCharsConservative($sourcePath),
            self::BASE64 => self::base64urlEncode($sourcePath),
        };
    }

    private static function urlencode(string $sourcePath): string
    {
        // Equivalent of JavaScript encodeURIComponent in PHP
        $revert = ['%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')'];
        return strtr(rawurlencode($sourcePath), $revert);
    }

    private static function base64IfUnsafeChars(string $sourcePath): string
    {
        if (preg_match('/^[a-zA-Z0-9-_.\/]+$/', $sourcePath) === 1) {
            // string contains only alphanumeric characters
            return self::urlencode($sourcePath);
        } else {
            // string contains unsafe characters -> base64 encode
            return self::base64urlEncode($sourcePath);
        }
    }

    private static function base64IfUnsafeCharsConservative(string $sourcePath): string
    {
        if (preg_match('/^[a-zA-Z0-9-_.]+$/', $sourcePath) === 1) {
            // string contains only alphanumeric characters
            return self::urlencode($sourcePath);
        } else {
            // string contains unsafe characters -> base64 encode
            return self::base64urlEncode($sourcePath);
        }
    }

    private static function base64urlEncode(string $sourcePath): string
    {
        return 'b64:' . rtrim(strtr(base64_encode($sourcePath), '+/', '-_'), '=');
    }
}
