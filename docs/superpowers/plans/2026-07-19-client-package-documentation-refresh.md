# Laravel Skir Client Documentation Refresh Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give `php-skir/client` a server-family header image, a concise standard-PHP quick start, and focused Laravel client guides with complete examples, then publish patch release `v0.1.2`.

**Architecture:** Keep `README.md` as the package landing page and shortest successful path. Put stable, responsibility-focused guidance in five Markdown files under `docs/`, reuse one `GetUser` schema throughout, and link to generator repositories for generator-owned reference material. The change is documentation and artwork only; the runtime API and Composer dependencies remain unchanged.

**Tech Stack:** Markdown, Skir schemas, generated PHP clients, Laravel service container, Saloon 4 mocks, Composer, PHPUnit, Git, GitHub CLI, PNG artwork.

---

## File map

- Create `art/banner.png`: 1600 by 600 client header using the approved typed-bridge composition.
- Modify `README.md`: concise landing page, standard-PHP quick start, generator alternatives, and guide index.
- Create `docs/generating-clients.md`: standard generator workflow, generated client use, Artisan wrapper, and low-level transport.
- Create `docs/laravel-data.md`: Laravel Data generator integration and typed client example.
- Create `docs/simple-data-objects.md`: Simple Data Objects generator integration and typed client example.
- Create `docs/configuration-and-codecs.md`: Laravel configuration, container/manual setup, and four codec choices.
- Create `docs/error-handling-and-testing.md`: exception handling and Saloon mock examples.
- Preserve `docs/superpowers/specs/2026-07-19-client-package-documentation-design.md`: approved design record.
- Preserve this plan at `docs/superpowers/plans/2026-07-19-client-package-documentation-refresh.md`.

### Task 1: Produce the typed-bridge client banner

**Files:**
- Create: `art/banner.png`
- Reference: `../server/art/banner.png`
- Reference: `../server/art/octopus-laravel.png`

- [ ] **Step 1: Inspect the server reference artwork**

Use the image viewer on both reference files and confirm the existing banner is 1600 by 600:

```bash
file ../server/art/banner.png ../server/art/octopus-laravel.png
```

Expected: the banner reports `PNG image data, 1600 x 600`; the octopus reports `1024 x 1024` with RGBA.

- [ ] **Step 2: Generate the final banner from both references**

Use the image-generation tool with the two reference paths and this prompt:

```text
Create a polished 1600x600 GitHub README header for “Laravel Skir Client” that is unmistakably the sibling of the supplied Laravel Skir Server banner. Preserve the same warm off-white paper background, Laravel-red palette, elegant editorial serif headline, small widely tracked uppercase package label, fine red connector lines, restrained glow, generous whitespace, and the supplied friendly red octopus character style.

Use the approved typed-bridge composition. Left side: small uppercase label “LARAVEL SKIR CLIENT”, headline “Type-safe, end to end.”, and supporting line “From your Laravel application to any SkirRPC endpoint.” Right side: a Laravel application card on the left and a Skir service card on the right, connected by the red octopus as the central bridge. Include small code-like labels “GetUserRequest” and “User” along the connection to communicate typed request and response DTOs. Keep all text legible and correctly spelled. Do not include language bubbles from the server artwork, logos, badges, gradients outside the existing restrained red glow, or extra decorative copy.
```

Save the selected generated result as `art/banner.png`.

- [ ] **Step 3: Verify dimensions and inspect the saved image**

```bash
file art/banner.png
```

Expected: `PNG image data, 1600 x 600`.

Open `art/banner.png` with the image viewer. Confirm every word is correct, the Laravel app and Skir service are visually distinct, the octopus matches the server-family character, and no elements are clipped at README width.

### Task 2: Replace the README with a concise standard-PHP quick start

**Files:**
- Modify: `README.md`
- Depends on: `art/banner.png`

- [ ] **Step 1: Replace the README header and positioning**

Start the file with the local image and these badges:

```markdown
![Laravel Skir Client](art/banner.png)

# Laravel Skir Client

[![Tests](https://github.com/php-skir/client/actions/workflows/tests.yml/badge.svg)](https://github.com/php-skir/client/actions/workflows/tests.yml)
[![Coverage](https://raw.githubusercontent.com/php-skir/client/badges/coverage.svg)](https://github.com/php-skir/client/actions/workflows/tests.yml)
[![Composer](https://img.shields.io/packagist/v/php-skir/client?label=composer&logo=composer)](https://packagist.org/packages/php-skir/client)
[![PHP](https://img.shields.io/badge/PHP-%5E8.3-777BB4?logo=php&logoColor=white)](https://packagist.org/packages/php-skir/client)
[![License](https://img.shields.io/github/license/php-skir/client)](LICENSE)

Laravel package for calling SkirRPC services through generated typed clients and Saloon.
```

Add a four-item feature list linking to the detailed guides:

```markdown
## Features

- Generate typed RPC clients from Skir schemas. See [Generating clients](docs/generating-clients.md).
- Use standard PHP objects, [Laravel Data](docs/laravel-data.md), or [Simple Data Objects](docs/simple-data-objects.md).
- Configure Laravel container resolution and matching wire codecs. See [Configuration and codecs](docs/configuration-and-codecs.md).
- Handle failures and test calls without network requests. See [Error handling and testing](docs/error-handling-and-testing.md).
```

- [ ] **Step 2: Add the standard-PHP quick-start installation and schema**

Use these exact commands:

```bash
composer require php-skir/client
npm install --save-dev skir skir-php-generator
```

Use this schema at `skir-src/admin/users.skir`:

```skir
struct GetUserRequest {
  user_id: int32;
}

struct User {
  user_id: int32;
  name: string;
}

method GetUser(GetUserRequest): User = 3180856469;
```

- [ ] **Step 3: Add generation and Composer configuration**

Use this root `skir.yml`:

```yaml
generators:
  - mod: skir-php-generator
    outDir: skir/skirout
    config:
      namespace: Skir
```

State in one sentence that Skir owns the output directory and may replace its contents. Then show:

```bash
npx skir gen
npx skir-php-generator configure-composer
composer dump-autoload
php artisan vendor:publish --tag=skir-client-config
```

- [ ] **Step 4: Add endpoint configuration and one typed call**

Use a separated base URL and endpoint:

```dotenv
SKIR_CLIENT_BASE_URL=https://api.example.test
SKIR_CLIENT_ENDPOINT=/api/skir
SKIR_CLIENT_CODEC=dense_json
```

Show the generated typed call with complete imports:

```php
use Skir\Admin\GetUserRequest;
use Skir\Admin\SkirRpcClient;
use Skir\Client\SkirClient as TransportSkirClient;

$client = new SkirRpcClient(app(TransportSkirClient::class));
$user = $client->getUser(new GetUserRequest(userId: 42));

echo $user->name;
```

- [ ] **Step 5: Finish with alternatives and the documentation index**

Keep this section brief: standard PHP is the dependency-light baseline; Laravel applications may instead use `skir-laravel-data-generator` or `skir-simple-data-objects-generator`. Link each package name to its dedicated local guide.

Add the five guide links under `## Documentation`. Do not restore the old long Generation or Codecs reference sections.

- [ ] **Step 6: Verify the README boundary and commit**

```bash
rg -n '^## ' README.md
git diff --check README.md
git add README.md art/banner.png
git commit -m "Refresh client README and artwork"
```

Expected headings: `Features`, `Quick start`, `Generator alternatives`, and `Documentation`. The commit includes only the README and banner.

### Task 3: Add standard generation and configuration guides

**Files:**
- Create: `docs/generating-clients.md`
- Create: `docs/configuration-and-codecs.md`

- [ ] **Step 1: Write `docs/generating-clients.md`**

Start with this standard-PHP contract and generator configuration:

```skir
struct GetUserRequest {
  user_id: int32;
}

struct User {
  user_id: int32;
  name: string;
}

method GetUser(GetUserRequest): User = 3180856469;
```

```yaml
generators:
  - mod: skir-php-generator
    outDir: skir/skirout
    config:
      namespace: Skir
```

Show the direct workflow:

```bash
npx skir gen
npx skir-php-generator configure-composer
composer dump-autoload
```

Then expand the client-specific details:

- Skir owns every `outDir` ending in `/skirout`; handwritten PHP stays elsewhere.
- `npx skir gen` is the direct generator command.
- `php artisan skir:generate-client` runs the configured Node compiler through Laravel and supports `--root`, `--skir-bin`, and `--node` overrides.
- `npx skir-php-generator configure-composer` adds the matching PSR-4 mapping; `composer dump-autoload` remains a separate command.
- The generated `Skir\Admin\SkirRpcClient` wraps `Skir\Client\SkirClient`, converts `GetUserRequest` with `toArray()`, and hydrates `User::fromArray()`.

Include this advanced low-level example after the generated-client path:

```php
use Skir\Admin\GetUserRequest;
use Skir\Admin\SkirMethods;
use Skir\Client\SkirClient;

$request = new GetUserRequest(userId: 42);

$response = app(SkirClient::class)->invoke(
    SkirMethods::getUser(),
    $request->toArray(),
);
```

State that generated clients are preferred because they restore the typed response object.

- [ ] **Step 2: Write `docs/configuration-and-codecs.md`**

Document the publish command, the three primary environment values, and this base URL rule: `SkirConnector` trims a trailing slash from `base_url`, while each request resolves the configured `endpoint`.

Show container resolution:

```php
use Skir\Client\SkirClient;

$client = app(SkirClient::class);
```

Show manual construction and each codec factory:

```php
use Skir\Client\Codecs\SkirClientCodecs;
use Skir\Client\SkirClient;

$denseJson = new SkirClient('https://api.example.test', '/api/skir');
$standardJson = new SkirClient(
    'https://api.example.test',
    '/api/skir',
    SkirClientCodecs::standardJson(),
);
$base64DenseJson = new SkirClient(
    'https://api.example.test',
    '/api/skir',
    SkirClientCodecs::base64DenseJson(),
);
$cbor = new SkirClient(
    'https://api.example.test',
    '/api/skir',
    SkirClientCodecs::cbor(),
);
```

List the matching environment values: `dense_json`, `standard_json`, `base64_dense_json`, and `cbor`. State that CBOR uses a binary request with `application/cbor`, requires `composer require spomky-labs/cbor-php`, and must match a CBOR server endpoint. State that every selected client codec must match the server endpoint codec.

- [ ] **Step 3: Verify both guides and commit**

```bash
rg -n 'GetUser|skirout|skir:generate-client|SkirClientCodecs|spomky-labs/cbor-php' docs/generating-clients.md docs/configuration-and-codecs.md
git diff --check docs/generating-clients.md docs/configuration-and-codecs.md
git add docs/generating-clients.md docs/configuration-and-codecs.md
git commit -m "Document client generation and configuration"
```

Expected: every searched concept appears in its relevant guide and `git diff --check` is silent.

### Task 4: Add the Laravel Data and Simple Data Objects guides

**Files:**
- Create: `docs/laravel-data.md`
- Create: `docs/simple-data-objects.md`

- [ ] **Step 1: Write `docs/laravel-data.md`**

Install with:

```bash
composer require php-skir/client spatie/laravel-data
npm install --save-dev skir skir-laravel-data-generator
```

Configure with:

```yaml
generators:
  - mod: skir-laravel-data-generator
    outDir: skir/skirout
    config:
      namespace: Skir
```

Use this source contract explicitly:

```skir
struct GetUserRequest {
  user_id: int32;
}

struct User {
  user_id: int32;
  name: string;
}

method GetUser(GetUserRequest): User = 3180856469;
```

Generate and configure Composer with:

```bash
npx skir gen
npx skir-laravel-data-generator configure-composer
composer dump-autoload
```

Show this typed call:

```php
use Skir\Admin\GetUserRequestData;
use Skir\Admin\SkirRpcClient;
use Skir\Client\SkirClient as TransportSkirClient;

$client = new SkirRpcClient(app(TransportSkirClient::class));
$user = $client->getUser(new GetUserRequestData(userId: 42));

echo $user->name;
```

Explain that generated responses pass through Laravel Data hydration and validation, `snake_case` input names map to camel-case properties, and direct struct arrays receive collection metadata where supported. Keep validation overlays to one client-relevant example and link to `https://github.com/php-skir/skir-laravel-data-generator` for the full reference.

- [ ] **Step 2: Write `docs/simple-data-objects.md`**

Install with:

```bash
composer require php-skir/client std-out/simple-data-objects
npm install --save-dev skir skir-simple-data-objects-generator
```

Configure with:

```yaml
generators:
  - mod: skir-simple-data-objects-generator
    outDir: skir/skirout
    config:
      namespace: Skir
```

Use this source contract:

```skir
struct GetUserRequest {
  user_id: int32;
}

struct User {
  user_id: int32;
  name: string;
}

method GetUser(GetUserRequest): User = 3180856469;
```

Generate and configure Composer with:

```bash
npx skir gen
npx skir-simple-data-objects-generator configure-composer
composer dump-autoload
```

Show the generated typed call explicitly:

```php
use Skir\Admin\GetUserRequestData;
use Skir\Admin\SkirRpcClient;
use Skir\Client\SkirClient as TransportSkirClient;

$client = new SkirRpcClient(app(TransportSkirClient::class));
$user = $client->getUser(new GetUserRequestData(userId: 42));

echo $user->name;
```

Add a client-side hydration example:

```php
use Skir\Admin\GetUserRequestData;

$request = GetUserRequestData::makeFromSkirPayload([
    'user_id' => 42,
]);
```

Explain that `makeFromSkirPayload()` validates untrusted named Skir data before recursive hydration, while inherited `from()` and collection helpers are trusted hydration paths. Mention immutable `with()`, mapped names, and direct struct `TypedDataCollection` behavior, then link to `https://github.com/php-skir/skir-simple-data-objects-generator` for the full reference.

- [ ] **Step 3: Verify generator naming and commit**

Compare the examples against the generator source assertions:

```bash
rg -n 'GetUserRequestData|makeFromSkirPayload|SkirRpcClient' ../skir-laravel-data-generator ../skir-simple-data-objects-generator
git diff --check docs/laravel-data.md docs/simple-data-objects.md
git add docs/laravel-data.md docs/simple-data-objects.md
git commit -m "Document Laravel client data generators"
```

Expected: both guides use their actual `Data`-suffixed classes and the diff check is silent.

### Task 5: Add error-handling and testing examples

**Files:**
- Create: `docs/error-handling-and-testing.md`

- [ ] **Step 1: Document the two exception paths**

Explain these exact behaviors:

- A non-successful HTTP response throws `SkirClientException` with message `Skir RPC request failed with status {status}.`, the method descriptor in `$exception->method`, and the Saloon response in `$exception->response`.
- A successful HTTP response that cannot be decoded throws `SkirClientException` with message `Skir RPC response for [{method}] could not be decoded.`, the method descriptor, a `null` response property, and the runtime decoding exception as `getPrevious()`.

Use this handling example:

```php
use Skir\Client\Exceptions\SkirClientException;

try {
    $user = $client->getUser($request);
} catch (SkirClientException $exception) {
    report($exception);

    if ($exception->response !== null) {
        logger()->warning('SkirRPC request failed.', [
            'method' => $exception->method?->name,
            'status' => $exception->response->status(),
        ]);
    }

    throw $exception;
}
```

- [ ] **Step 2: Add a complete generated-client test**

Use a PHPUnit class with complete imports for `MockClient`, `MockResponse`, `Request`, `SkirRpcRequest`, generated `GetUserRequest`, generated `SkirRpcClient`, and the transport alias. The happy-path core is:

```php
$transport = app(TransportSkirClient::class);
$mockClient = new MockClient([
    SkirRpcRequest::class => MockResponse::make('[42,"Maxim"]', 200, [
        'Content-Type' => 'application/json',
    ]),
]);
$transport->withMockClient($mockClient);

$client = new SkirRpcClient($transport);
$user = $client->getUser(new GetUserRequest(userId: 42));

$this->assertSame(42, $user->userId);
$this->assertSame('Maxim', $user->name);

$mockClient->assertSent(function (Request $request): bool {
    return $request instanceof SkirRpcRequest
        && $request->resolveEndpoint() === '/api/skir'
        && $request->body()->all() === [
            'method' => 'GetUser',
            'request' => [42],
        ];
});
```

Precede it with `config()->set('skir-client.base_url', 'https://api.example.test')` and `config()->set('skir-client.endpoint', '/api/skir')` so the endpoint assertion is deterministic.

- [ ] **Step 3: Add the HTTP failure test and commit**

Show this second test using a 404 mock and the package's current `SkirClientException` API:

```php
public function test_it_exposes_failed_http_responses(): void
{
    config()->set('skir-client.base_url', 'https://api.example.test');
    config()->set('skir-client.endpoint', '/api/skir');

    $transport = app(TransportSkirClient::class);
    $mockClient = new MockClient([
        SkirRpcRequest::class => MockResponse::make([], 404),
    ]);
    $transport->withMockClient($mockClient);

    $client = new SkirRpcClient($transport);

    try {
        $client->getUser(new GetUserRequest(userId: 42));
        $this->fail('Expected a failed SkirRPC response.');
    } catch (SkirClientException $exception) {
        $this->assertSame('Skir RPC request failed with status 404.', $exception->getMessage());
        $this->assertSame('GetUser', $exception->method?->name);
        $this->assertSame(404, $exception->response?->status());
    }
}
```

```bash
rg -n 'failed with status|could not be decoded|MockClient|assertSent|404' docs/error-handling-and-testing.md
git diff --check docs/error-handling-and-testing.md
git add docs/error-handling-and-testing.md
git commit -m "Document client errors and testing"
```

Expected: the guide contains both failure classes and both test paths.

### Task 6: Verify the complete documentation set

**Files:**
- Verify: `README.md`
- Verify: `art/banner.png`
- Verify: `docs/generating-clients.md`
- Verify: `docs/laravel-data.md`
- Verify: `docs/simple-data-objects.md`
- Verify: `docs/configuration-and-codecs.md`
- Verify: `docs/error-handling-and-testing.md`

- [ ] **Step 1: Verify all local README targets exist**

Run each check separately:

```bash
test -f art/banner.png
test -f docs/generating-clients.md
test -f docs/laravel-data.md
test -f docs/simple-data-objects.md
test -f docs/configuration-and-codecs.md
test -f docs/error-handling-and-testing.md
```

Expected: every command exits with status 0.

- [ ] **Step 2: Validate Composer metadata**

```bash
composer validate --strict
```

Expected: `composer.json is valid` with no errors.

- [ ] **Step 3: Run the complete client test suite**

```bash
composer test
```

Expected: PHPUnit exits 0 with all tests passing.

- [ ] **Step 4: Run final content checks**

```bash
file art/banner.png
git diff --check main...HEAD
git status --short
```

Expected: the image is 1600 by 600, the diff check is silent, and the feature branch is clean.

- [ ] **Step 5: Review the rendered README and commit any corrections**

Inspect heading hierarchy, code-block rendering, local links, image scaling, concise wording, and the consistency of `GetUser` names across every guide. If corrections are required:

```bash
git add README.md art/banner.png docs
git commit -m "Polish client documentation"
```

If no corrections are required, do not create an empty commit.

### Task 7: Integrate, tag, and release `v0.1.2`

**Files:**
- No source-file changes expected.
- Git refs: `main`, `v0.1.2`
- GitHub release: `v0.1.2`

- [ ] **Step 1: Confirm branch and remote state**

```bash
git status --short --branch
git log --oneline --decorate main..HEAD
gh repo view --json nameWithOwner,defaultBranchRef
gh release view v0.1.2
```

Expected: the feature branch is clean; its commits are listed; the repository is `php-skir/client` with `main` as default; and `gh release view` reports that `v0.1.2` does not yet exist.

- [ ] **Step 2: Fast-forward `main` and push**

```bash
git switch main
git merge --ff-only docs/client-package-refresh
git push origin main
```

Expected: `main` advances to the verified documentation commit and the push succeeds.

- [ ] **Step 3: Create and push the lightweight tag**

Match the repository's existing lightweight tags:

```bash
git tag v0.1.2
git push origin v0.1.2
```

Expected: the tag points at the new `main` HEAD and the push succeeds.

- [ ] **Step 4: Create the GitHub release**

```bash
gh release create v0.1.2 --title "v0.1.2" --notes "Expanded Laravel 10–13 compatibility and refreshed the client package documentation with a standard-PHP quick start, dedicated Laravel Data and Simple Data Objects guides, configuration and codec examples, error-handling and testing guidance, and new Laravel Skir Client artwork."
```

Expected: GitHub creates the `v0.1.2` release without changing the tag target.

- [ ] **Step 5: Verify the published state**

```bash
git status --short --branch
git rev-parse HEAD
git rev-parse v0.1.2
gh release view v0.1.2 --json tagName,name,isDraft,isPrerelease,url
```

Expected: `main` is clean and aligned with `origin/main`; `HEAD` and `v0.1.2` resolve to the same commit; the release is neither draft nor prerelease.
