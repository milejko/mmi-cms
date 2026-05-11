# mmi-cms

A headless-capable CMS library built on top of the [MMi Framework](https://github.com/milejko/mmi). It provides a structured content tree (categories), a flexible template/widget system, a REST JSON API, a built-in admin panel (`CmsAdmin`), file management, mail, tagging, cron, and role-based access control — all wired together via [PHP-DI](https://php-di.org/).

---

## Requirements

| Dependency | Version |
|---|---|
| PHP | ≥ 8.1 |
| mmi/mmi | ^5.0 |
| phpmailer/phpmailer | ^6.0 |

---

## Installation

```bash
composer require mmi/mmi-cms
```

Then bootstrap in your application's DI config (e.g. `src/App/di.app.php`). The CMS ships several partial DI files that you include individually:

| DI file | What it registers |
|---|---|
| `src/Cms/di.cms.php` | Core services: `AppEventInterceptorInterface`, `AuthProviderInterface`, `CmsScopeConfig`, `CmsCategoryRepository` |
| `src/Cms/di.api.php` | `MenuServiceInterface`, `StructureServiceInterface` |
| `src/Cms/di.auth.php` | `AuthInterface` (wires `Auth` ↔ `AuthProviderInterface`, injects into `ActionHelper` and `View`) |
| `src/Cms/di.acl.php` | `AclInterface` with default allow rules for `admin`, `guest`, and `cmsAdmin:index:login` |
| `src/Cms/di.navigation.php` | `Navigation` (requires a `NavigationConfig` binding to be present) |

Minimal required bindings you must provide in your own DI config:

```php
return [
    NavigationConfig::class => autowire(YourNavigationConfig::class),
    CmsSkinsetConfig::class => fn($c) => (new CmsSkinsetConfig())->addSkin(new YourSkinConfig()),
];
```

Deploy the database schema:

```bash
./bin/mmi Mmi:DbDeploy
```

---

## Environment variables

| Variable | Default | Description |
|---|---|---|
| `CMS_AUTH_SALT` | `better-use-some-random-salt` | Salt used for password hashing — **always override** |
| `CMS_LANGUAGE_DEFAULT` | `pl` | Default admin UI language |
| `CMS_LANGUAGE_LIST` | `pl,en` | Comma-separated list of available languages |
| `CMS_THUMB_QUALITY` | `85` | WebP / JPEG thumbnail quality (%) |
| `APP_DEBUG_ENABLED` | — | Enable debug mode |
| `APP_VIEW_CDN` | — | CDN base URL for assets |
| `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_NAME`, `DB_PASSWORD` | — | Database connection |
| `CACHE_SYSTEM_ENABLED`, `CACHE_PUBLIC_ENABLED` | — | Cache toggles |
| `SESSION_*` | — | Session handler configuration |
| `LOG_HANDLER` | — | Logger handler |

Copy `.env.sample` to `.env` and fill in the values.

---

## Architecture overview

```
CmsSkinsetConfig          ← registry of skins (one per frontend site / scope)
  └─ CmsSkinConfig        ← a skin: key, name, attributes, front URL, preview path
       ├─ CmsTemplateConfig   ← a page type; maps to a controller class
       │    ├─ CmsSectionConfig  ← a named content area within a template
       │    │    └─ CmsWidgetConfig  ← a pluggable content block; maps to a controller class
       │    └─ (compatible children keys, allowed-on-root flag, cache TTL)
       └─ (menu max depth, skin attributes passed to the API)
```

### Skins (scopes)

A **skin** represents a frontend application (or a section of one). Each skin has a unique `key` used as the scope identifier in API URLs. Multiple skins can share the same backend database.

```php
$skin = (new CmsSkinConfig())
    ->setKey('my-site')
    ->setName('My Site')
    ->setFrontUrl('https://my-site.example.com')
    ->setPreviewPath('/preview')
    ->setAttributes(['theme' => 'dark'])
    ->setMenuMaxDepthReturned(3);
```

### Templates

A **template** defines a page type. Each template maps to a PHP controller that extends `AbstractTemplateController`. Templates declare which sections (and therefore which widgets) they support.

```php
$template = (new CmsTemplateConfig())
    ->setKey('article')
    ->setName('Article page')
    ->setControllerClassName(MyArticleController::class)
    ->setAllowedOnRoot(false)
    ->setCompatibleChildrenKeys(['article', 'folder'])
    ->setCacheLifeTime(3600)
    ->addSection(
        (new CmsSectionConfig())
            ->setKey('main')
            ->setName('Main content')
            ->addWidget(
                (new CmsWidgetConfig())
                    ->setKey('text')
                    ->setName('Text block')
                    ->setControllerClassName(MyTextWidgetController::class)
                    ->setMinOccurrence(0)
                    ->setMaxOccurrence(10)
            )
    );

$skin->addTemplate($template);
```

### Template controllers

Extend `AbstractTemplateController` and implement `getTransportObject()` to return a `TransportInterface` (typically a `TemplateDataTransport`). This is called by the REST API.

```php
class MyArticleController extends AbstractTemplateController
{
    public function getTransportObject(): TransportInterface { ... }
}
```

### Widget controllers

Extend `AbstractWidgetController` and implement `getDataObject()` returning a `DataInterface` (typically a `WidgetData`). File attachments are available via `getAttachments()` / `getAttachment()`.

```php
class MyTextWidgetController extends AbstractWidgetController
{
    public function getDataObject(): DataInterface { ... }
}
```

---

## REST API

Routes are registered by `CmsRouterConfig`. All responses are JSON.

| Method | URL pattern | Description |
|---|---|---|
| `GET` | `/api` | List all registered skins with HATEOAS links |
| `GET` | `/api/{scope}` | Skin config: attributes, templates, links |
| `GET` | `/api/{scope}/contents` | Flat list of all published categories |
| `GET` | `/api/{scope}/contents/{uri}` | Single category by URI (with widgets) |
| `GET` | `/api/{scope}/contents/preview/{id}/{originalId}/{authId}/{time}` | Unpublished preview by ID |
| `GET` | `/api/{scope}/contents/preview/{uri}` | Published preview by URI |
| `GET` | `/api/{scope}/structure` | Tree structure of categories (navigation) |
| `GET` | `/api/contents/id/{id}` | Redirect resolver: ID → canonical URL |

Responses for category endpoints include rendered widget data, breadcrumbs, and HATEOAS links. Responses for missing or misconfigured content return `404` with `{"message":"..."}`.

Caching is handled transparently via `CacheInterface`; use `CMS_THUMB_QUALITY` and cache config vars to tune performance.

---

## Admin panel

The `CmsAdmin` module is mounted at `/cmsAdmin` and provides:

- **Category management** — tree editor, drag-and-drop ordering, ACL per category, trash, widget placement
- **File manager** — upload, thumbnail generation (WebP / JPEG via scale / scalex / scaley / scalecrop)
- **User management** — `CmsAuth` records with roles, password hashing with `CMS_AUTH_SALT`
- **Mail** — mail server config, mail definitions, send log
- **Tags** — tag management and relations
- **Cron jobs** — schedule and manual execution
- **Cache management** — flush from the UI

File serving routes:

| Route | Pattern |
|---|---|
| Default thumb | `data/default/{hash}{name}.webp` |
| Scaled thumb | `data/{scale}/{dimensions}/{hash}{name}.webp` |
| Download | `data/download/{hash}{name}/{targetName}` |

---

## Authentication

`AuthProvider` implements `AuthProviderInterface` from the MMi Framework. It:

- Authenticates against the `CmsAuth` ORM table (salted SHA-512 hashes)
- Supports optional LDAP via `Mmi\Ldap`
- Records last login IP (respects `X-Forwarded-For`), last failed IP, and fail counters
- Returns roles from `CmsAuthRecord` (falls back to `['guest']` when empty)

To customise authentication, bind your own implementation to `AuthProviderInterface::class` in your DI config.

---

## CLI commands

| Command | Class | Description |
|---|---|---|
| `Cms:CategoryRebuild` | `CategoryRebuildCommand` | Rebuilds the category path/URI tree |
| `Cms:CronExecute` | `CronExecuteCommand` | Runs all due cron jobs |
| `Cms:FileGarbageCollector` | `FileGarbageCollectorCommand` | Removes orphaned uploaded files |

Run via the MMi console:

```bash
./bin/mmi Cms:CategoryRebuild
./bin/mmi Cms:CronExecute
./bin/mmi Cms:FileGarbageCollector
```

---

## Development

### Running tests

```bash
composer test:phpunit          # PHPUnit with coverage
composer test:phpstan          # Static analysis (level 1)
composer test:phpcs            # Coding style (phpcs)
composer test:phpmd            # Mess detection
composer test:all              # All of the above + security checker
```

Quick run without coverage:

```bash
./vendor/bin/phpunit --no-coverage
```

### Code style fixers

```bash
composer fix:phpcbf            # PHP Code Beautifier
composer fix:php-cs-fixer      # php-cs-fixer on src/ and tests/
composer fix:all               # Both
```

### Docker

A `Dockerfile` is included for CI and local development:

```bash
docker build --build-arg PHP_VERSION=8.5 -t mmi-cms .
docker run --rm mmi-cms ./vendor/bin/phpunit --no-coverage
```

---

## Links

- [MMi Framework](https://github.com/milejko/mmi) — the underlying MVC framework, DI container, ORM, cache, auth, and HTTP layers
- [PHP-DI](https://php-di.org/) — dependency injection container used for wiring
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) — mail transport

