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
    listener_priority: 15 # optional, defaults to 15
```

Add the corresponding `.env` variables:

```dotenv
APP_DEFAULT_LOCALE=en
APP_SUPPORTED_LOCALES=["en","es"]
```

---

## Contents

Classes are grouped by type, like the rest of the `letkode/*` packages:

```
src/
  LetkodeLocaleBundle.php
  EventListener/LocaleListener.php
  Provider/LocaleProvider.php
  Applier/TranslatableFieldApplier.php
  Trait/HasEnumTranslationLabelTrait.php
  Trait/HasTranslationsTrait.php
```

### `Provider\LocaleProvider`

Resolves the active locale for the current request. Inject it in services that need locale-awareness.

```php
use Letkode\LocaleBundle\Provider\LocaleProvider;

public function __construct(private readonly LocaleProvider $localeProvider) {}

public function doSomething(): void
{
    $locale = $this->localeProvider->get(); // 'en' | 'es' | ...
}
```

### `EventListener\LocaleListener`

Kernel event listener (priority configurable via `listener_priority`, defaults to `15`) that sets `$request->setLocale()` on every request, from the `Accept-Language` header (first matching supported locale) or the configured `default_locale` as fallback.

### `Applier\TranslatableFieldApplier`

Applies a translatable field — either a plain string (default locale only) or a locale map — to an entity, storing every locale's value via the entity's `setTranslation()` (e.g. `Trait\HasTranslationsTrait`, below).

```php
use Letkode\LocaleBundle\Applier\TranslatableFieldApplier;

// $entity has: public array|null $translations = null; and setTranslation(string $locale, string $field, string|null $value): void

$this->translatableFieldApplier->apply($entity, 'name', ['en' => 'Module', 'es' => 'Módulo']);
// $entity->name is now 'Module' (default locale), $entity->translations holds both
```

### `Trait\HasEnumTranslationLabelTrait`

Adds a translator-backed `getLabel()`/`getTranslations()` pair to a string-backed enum, so business enums (statuses, types, etc.) resolve their human-readable label from a `{domain}.{locale}.yaml` translation file instead of hardcoding it.

```php
use Letkode\LocaleBundle\Trait\HasEnumTranslationLabelTrait;

enum IdentityTypeEnum: string
{
    use HasEnumTranslationLabelTrait;

    case PRINCIPAL = 'principal';

    private static function translationDomain(): string
    {
        return 'identity_type'; // reads identity_type.{locale}.yaml
    }
}

$label = IdentityTypeEnum::PRINCIPAL->getLabel($translator); // translated for the current locale
```

### `Trait\HasTranslationsTrait`

Adds a `translations` jsonb column (via a Doctrine `#[ORM\Column]` attribute) for storing multi-locale field values on an entity — the storage shape `TranslatableFieldApplier` writes into.

```php
use Letkode\LocaleBundle\Trait\HasTranslationsTrait;

#[ORM\Entity]
class Product
{
    use HasTranslationsTrait;
}

$entity->setTranslation('es', 'name', 'Producto');
$entity->getTranslation('es', 'name'); // 'Producto'
```

> **Requires `doctrine/orm`.** Unlike the rest of this bundle, this trait is Doctrine-specific — `doctrine/orm` is listed in `suggest`, not `require`, so projects that only use `LocaleProvider`/`LocaleListener`/`TranslatableFieldApplier` don't need it installed. Only use this trait in an app that already has Doctrine ORM.

---

## Requirements

- PHP `^8.4`
- Symfony `^7.0 || ^8.0`
- `doctrine/orm` `^3.0` — only if using `Trait\HasTranslationsTrait`

---

## License

MIT — see [LICENSE](LICENSE).
