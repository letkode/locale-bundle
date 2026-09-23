<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Trait;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Adds a translated human-readable label to a string-backed enum. Each case's key is
 * `{translationDomain()}.{case value}` in a `{domain}.{locale}.yaml` translation file.
 */
trait HasEnumTranslationLabelTrait
{
    abstract private static function translationDomain(): string;

    public function getLabel(TranslatorInterface $translator): string
    {
        return $translator->trans(self::translationDomain() . '.' . $this->value, domain: self::translationDomain());
    }

    /**
     * Label resolved for every given locale, not just the current request's.
     *
     * @param string[] $locales
     *
     * @return array<string, array{label: string}>
     */
    public function getTranslations(TranslatorInterface $translator, array $locales, string $labelKey = 'label'): array
    {
        $translations = [];
        foreach ($locales as $locale) {
            $translations[$locale] = [
                $labelKey => $translator->trans(self::translationDomain() . '.' . $this->value,
                    domain: self::translationDomain(),
                    locale: $locale
                ),
            ];
        }

        return $translations;
    }
}
