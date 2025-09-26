<?php

namespace Sandstorm\LaravelImagor\Enums;

enum SourceUrlMode: string
{
    case PLAIN = 'plain';
    case ENCODED = 'encoded';

    public static function getDefault(): self
    {
        return self::ENCODED;
    }

    public static function fromString(string $mode): ?self
    {
        return self::tryFrom(strtolower($mode));
    }
}
