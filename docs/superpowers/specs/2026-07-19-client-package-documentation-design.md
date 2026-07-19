# Laravel Skir Client documentation refresh

## Objective

Turn `php-skir/client` into a clearer Laravel-focused package entry point without expanding its runtime API. The README should get a Laravel developer from installation to one successful typed RPC call quickly, while focused guides explain generator alternatives, configuration, codecs, failures, and testing in more depth.

The work is documentation and artwork only. It does not change PHP behavior or dependencies.

## Audience and success criteria

The primary reader is a Laravel developer consuming a SkirRPC endpoint. The documentation succeeds when that reader can:

1. Understand what the client package provides.
2. Generate a standard-PHP typed client and call one method from Laravel.
3. Choose Laravel Data or Simple Data Objects when those DTO models suit the application better.
4. Configure the endpoint and matching wire codec.
5. Handle transport and decoding failures.
6. Test generated client calls without making network requests.

Examples must use real public APIs, include necessary imports, and carry one consistent `GetUser` example through the documentation. Generator-specific guides explain client integration and link to their generator repositories rather than duplicating the generators' full reference documentation.

## Header artwork

Add `art/banner.png` at 1600 by 600 pixels and reference it at the top of `README.md`.

The banner will match the server package's visual family:

- warm off-white background;
- Laravel-red palette;
- editorial serif headline with a small uppercase package label;
- thin red connector lines;
- the same friendly red octopus illustration style.

The selected composition is the typed bridge:

- label: `Laravel Skir Client`;
- headline: `Type-safe, end to end.`;
- supporting line: `From your Laravel application to any SkirRPC endpoint.`;
- visual flow: Laravel application, red octopus bridge, Skir service;
- small request and response DTO labels to communicate typed calls.

The composition should be recognizably related to the server banner without reusing its whole-stack language or surrounding-language layout.

## README design

`README.md` remains a concise landing page and quick start. It contains:

1. The local banner, existing status badges, and a one-sentence package description.
2. A short feature list with links to the detailed guides.
3. A standard-PHP generator quick start that:
   - installs `php-skir/client`, `skir`, and `skir-php-generator`;
   - defines a small `GetUser` Skir method;
   - configures a generator-owned output directory ending in `/skirout`;
   - runs generation and Composer configuration;
   - publishes/configures the Laravel client endpoint;
   - constructs the generated request and performs one typed call through the generated `SkirRpcClient` using the container-resolved transport.
4. A short generator-alternatives section that identifies standard PHP as the baseline and links to the Laravel Data and Simple Data Objects guides.
5. A documentation index.

The existing long sections about generator command internals, codec variants, and low-level transport calls move into the guides.

## Documentation structure

### `docs/generating-clients.md`

Explain the shared generation workflow using the standard PHP generator. Cover generated-directory ownership, Composer mapping, `npx skir gen`, the optional `php artisan skir:generate-client` wrapper, typed generated requests and responses, and the relationship between `SkirRpcClient` and the lower-level `SkirClient` transport. Retain direct `SkirClient::invoke()` as an advanced escape hatch for debugging and custom integrations.

### `docs/laravel-data.md`

Show the dependencies and `skir.yml` configuration for `skir-laravel-data-generator`, then make the same typed `GetUser` call with generated Laravel Data request and response classes. Explain response hydration, validation, mapped names, and collection behavior only as they affect client use. Link to the generator repository for its complete configuration and validation reference.

### `docs/simple-data-objects.md`

Show the dependencies and configuration for `skir-simple-data-objects-generator`, followed by the same typed call with generated immutable DTOs. Explain `makeFromSkirPayload()`, generated validation, typed collections, and the trusted-versus-untrusted hydration distinction as it affects client code. Link to the generator repository for full reference documentation.

### `docs/configuration-and-codecs.md`

Explain publishing `config/skir-client.php`, the `base_url` and `endpoint` split, Laravel container resolution, and manual construction. Include complete examples for dense JSON, standard JSON, base64 dense JSON, and CBOR. State that the client codec must match the server endpoint and that CBOR requires the optional `spomky-labs/cbor-php` dependency.

### `docs/error-handling-and-testing.md`

Distinguish failed HTTP responses from response-decoding failures. Show how to catch `SkirClientException` and inspect its method and optional response. Demonstrate Saloon's `MockClient` with both the transport and a generated typed client. Assertions cover the method name, endpoint, encoded request, decoded response, and an HTTP failure path.

## Example consistency

Every guide uses the same conceptual schema:

- `GetUserRequest` with `user_id`;
- `User` with `user_id` and `name`;
- `GetUser(GetUserRequest): User`.

Generated PHP names follow each generator's actual conventions: standard PHP uses `GetUserRequest` and `User`, while the Laravel Data and Simple Data Objects generators use their generated `Data` suffixes. Examples will be checked against the current generator source and tests before release.

## Verification

Before integration:

1. Verify every relative README and docs link resolves.
2. Confirm the banner is a 1600 by 600 PNG and inspect it visually.
3. Check all code examples against the client and generator public APIs.
4. Run `composer validate --strict` in the client repository.
5. Run the complete client PHPUnit suite with `composer test`.
6. Run `git diff --check`.
7. Review the rendered README for hierarchy, scanability, and concise wording.

## Release

The next client patch release is `v0.1.2`, following `v0.1.1`. It will include both this documentation and artwork refresh and the Laravel 10 through 13 dependency compatibility change already present on `main` after the previous tag. No Composer version field needs to change because the package version comes from the Git tag.

After verification and approval, merge the feature branch into `main`, push it, create and push tag `v0.1.2`, and create the corresponding GitHub release. Release notes mention the expanded Laravel compatibility, the standard-PHP quick start, the generator-specific guides, and the new banner.
