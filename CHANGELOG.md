# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [1.0.1] - 2026-06-19

### Fixed
- Replace incorrect `getConfiguration()` override with `configure(DefinitionConfigurator)` — the correct `AbstractBundle` API for defining bundle config schema

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

[Unreleased]: https://github.com/letkode/locale-bundle/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/letkode/locale-bundle/releases/tag/v1.0.0
