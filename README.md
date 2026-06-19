# letkode/locale-bundle

Locale provider, listener and translatable field applier for Symfony applications.

---

## Installation

```bash
composer require letkode/locale-bundle
```

Symfony Flex will register the bundle automatically. If not using Flex, add it manually:

```php
// config/bundles.php
return [
    Letkode\LocaleBundle\LetkodeLocaleBundle::class => ['all' => true],
];
```

---

## Configuration

Create `config/packages/letkode_locale.yaml`:

```yaml
letkode_locale:
    default_locale: '%env(APP_DEFAULT_LOCALE)%'
    supported_locales: '%env(json:APP_SUPPORTED_LOCALES)%'
```

Add the corresponding `.env` variables:

```dotenv
APP_DEFAULT_LOCALE=en
APP_SUPPORTED_LOCALES=["en","es"]
```

---

## Contents

### `LocaleProvider`

Resolves the active locale for the current request. Inject it in services that need locale-awareness.

```php
public function __construct(private readonly LocaleProvider $localeProvider) {}

public function doSomething(): void
{
    $locale = $this->localeProvider->getLocale(); // 'en' | 'es' | ...
}
```

### `LocaleListener`

Kernel event listener (priority 20) that sets `$request->setLocale()` on every request. Reads from:

1. `_locale` query parameter
2. `Accept-Language` header (first matching supported locale)
3. Configured `default_locale` as fallback

### `TranslatableFieldApplier`

Applies translated values from a `translations` jsonb map to entity fields for the active locale.

```php
// Entity has: public array|null $translations = null; (via HasTranslationsTrait)
// translations = {"en": {"name": "Module"}, "es": {"name": "Módulo"}}

$this->translatableFieldApplier->apply($entity, ['name']);
// $entity->name is now set to the locale-specific value
```

---

## Requirements

- PHP `^8.4`
- Symfony `^7.0 || ^8.0`

---

## License

MIT — see [LICENSE](LICENSE).
