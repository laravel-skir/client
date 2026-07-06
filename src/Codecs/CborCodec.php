<?php

declare(strict_types=1);

namespace LaravelSkir\Client\Codecs;

use CBOR\Decoder;
use CBOR\Encoder;
use CBOR\StringStream;
use LaravelSkir\Runtime\DenseJson;
use LaravelSkir\Runtime\Exceptions\SkirRuntimeException;
use LaravelSkir\Runtime\MethodDescriptor;
use Throwable;

final class CborCodec implements SkirClientHttpCodec
{
    public function encodeRequest(MethodDescriptor $descriptor, mixed $request): mixed
    {
        return DenseJson::encode($descriptor->requestType, $request);
    }

    public function encodeRequestBody(MethodDescriptor $descriptor, mixed $request): string
    {
        try {
            return (new Encoder)->encode([
                'method' => $descriptor->name,
                'request' => $this->encodeRequest($descriptor, $request),
            ]);
        } catch (Throwable $exception) {
            throw SkirRuntimeException::invalidValue("Skir CBOR request could not be encoded: {$exception->getMessage()}");
        }
    }

    public function decodeResponse(MethodDescriptor $descriptor, string $response): mixed
    {
        try {
            $value = Decoder::create()
                ->decode(StringStream::create($response))
                ->normalize();
        } catch (Throwable $exception) {
            throw SkirRuntimeException::invalidValue("Skir CBOR response is invalid: {$exception->getMessage()}");
        }

        return DenseJson::decode($descriptor->responseType, $value);
    }

    public function contentType(): string
    {
        return 'application/cbor';
    }
}
