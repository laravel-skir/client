<?php

declare(strict_types=1);

namespace LaravelSkir\Client\Codecs;

use CBOR\Encoder;
use LaravelSkir\Client\Exceptions\SkirClientException;

final class SkirClientCodecs
{
    public static function cbor(): SkirClientCodec
    {
        if (! class_exists(Encoder::class)) {
            throw SkirClientException::missingCborDependency();
        }

        return new CborCodec;
    }

    public static function denseJson(): SkirClientCodec
    {
        return new DenseJsonCodec;
    }

    public static function standardJson(): SkirClientCodec
    {
        return new StandardJsonCodec;
    }

    public static function base64DenseJson(): SkirClientCodec
    {
        return new Base64DenseJsonCodec;
    }
}
