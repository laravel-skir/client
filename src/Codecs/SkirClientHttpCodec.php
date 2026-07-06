<?php

declare(strict_types=1);

namespace LaravelSkir\Client\Codecs;

use LaravelSkir\Runtime\MethodDescriptor;

interface SkirClientHttpCodec extends SkirClientCodec
{
    public function encodeRequestBody(MethodDescriptor $descriptor, mixed $request): string;

    public function contentType(): string;
}
