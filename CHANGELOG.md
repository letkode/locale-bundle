# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.1.0] - 2026-10-06

### Added
- `extra.letkode.publish` in `composer.json` declares `resources/config/letkode_locale.yaml.dist` as a publishable example config, so `vendor/bin/letkode-publish locale` copies it into the project (see `letkode/config-publisher`).
- `letkode/config-publisher` is now a `require`; it only ships the `letkode-publish` executable and is never used by the bundle's code.
- `info()` on every config option, shown by `bin/console config:dump-reference letkode_locale`.

### Removed
- `recipes/letkode/locale-bundle/1.0/`: it was never published to a recipes repository, so Flex never applied it. Flex registers the bundle from its auto-generated recipe; use `letkode-publish locale` for the config file.

### Fixed
- README documented `supported_locales` as a list (`["en","es"]`), but `LocaleListener` reads it as a `{code: label}` map (`array_keys`). With a list it matched `0`/`1` instead of the locale codes.

---

## [2.0.0] - 2026-09-23

### Added
- `Trait\HasEnumTranslationLabelTrait` — adds translator-backed `getLabel()`/`getTranslations()` to string-backed enums, reading `{translationDomain()}.{value}` from a `{domain}.{locale}.yaml` translation file
- `Trait\HasTranslationsTrait` — moved from `letkode/orm-toolkit-bundle` (`Trait\Entity\HasTranslationsTrait`); adds a `translations` jsonb column for multi-locale field storage. `doctrine/orm` is a `suggest`, not a hard `require` — only needed by apps that use this specific trait

### Changed
- Reorganized classes by type, matching the convention used by the other `letkode/*` packages (`Trait/`, `Attribute/`, `Exception/`, etc. — not by domain): `LocaleListener` → `EventListener\LocaleListener`, `LocaleProvider` → `Provider\LocaleProvider`, `TranslatableFieldApplier` → `Applier\TranslatableFieldApplier` (all were flat under `Letkode\LocaleBundle\`)
- Added `symfony/translation-contracts` (`^3.0`) as a require, for `HasEnumTranslationLabelTrait`'s `TranslatorInterface` dependency
- Added `doctrine/orm` (`^3.0`) to `require-dev`, so this bundle's own CI can test/analyze `HasTranslationsTrait`

### Migration
- Update imports: `Letkode\LocaleBundle\LocaleProvider` → `Letkode\LocaleBundle\Provider\LocaleProvider`, `Letkode\LocaleBundle\LocaleListener` → `Letkode\LocaleBundle\EventListener\LocaleListener`, `Letkode\LocaleBundle\TranslatableFieldApplier` → `Letkode\LocaleBundle\Applier\TranslatableFieldApplier`
- If using the old `Letkode\OrmToolkitBundle\Trait\Entity\HasTranslationsTrait` (`letkode/orm-toolkit-bundle`), switch to `Letkode\LocaleBundle\Trait\HasTranslationsTrait` (`letkode/locale-bundle`) — same behavior, moved package. See `letkode/orm-toolkit-bundle`'s own CHANGELOG for the removal.

---

## [1.0.4] - 2026-07-07

### Added
- `listener_priority` config option to make `LocaleListener`'s `kernel.request` priority configurable (default `15`, matching the previous hardcoded value)

### Changed
- `LocaleListener` is no longer an `EventSubscriberInterface`; it's now registered via a `kernel.event_listener` tag in `services.yaml` so its priority can be resolved from the `letkode.locale.listener_priority` parameter

---

## [1.0.3] - 2026-06-19

### Fixed
- Use `variableNode` for `supported_locales` to allow `%env(json:...)%` dynamic values

---

## [1.0.2] - 2026-06-19

### Fixed
- Replace incorrect `getConfiguration()` override with `configure(DefinitionConfigurator)` — the correct `AbstractBundle` API for defining bundle config schema

---

## [1.0.1] - 2026-06-19

> **Note:** This tag was published but its Packagist zip was cached before correction. Use `1.0.2` instead.

### Added
- Symfony Flex recipe for auto-configuration: `manifest.json` registers the bundle and copies `config/packages/letkode_locale.yaml` with `APP_DEFAULT_LOCALE` and `APP_SUPPORTED_LOCALES` env vars

---

## [1.0.0] - 2026-06-19

### Added
- Initial release as `letkode/locale-bundle`
- Symfony bundle integration via `LetkodeLocaleBundle` extending `AbstractBundle`
- Auto-discovery support via `extra.symfony.bundles` in Composer
- Bundle configuration via `letkode_locale` key: `default_locale` and `supported_locales`
- **`LocaleProvider`**: resolves the active locale from the current request
- **`LocaleListener`**: sets the application locale on each request from `Accept-Language` or query param, with fallback to configured default
- **`TranslatableFieldApplier`**: applies translated field values from a `translations` jsonb map for the active locale

### Requirements
- PHP `^8.4`
- Symfony `^7.0 || ^8.0`

[Unreleased]: https://github.com/letkode/locale-bundle/compare/1.0.4...HEAD
[1.0.4]: https://github.com/letkode/locale-bundle/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/letkode/locale-bundle/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/letkode/locale-bundle/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/letkode/locale-bundle/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/letkode/locale-bundle/releases/tag/1.0.0
