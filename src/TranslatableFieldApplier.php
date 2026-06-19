<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class TranslatableFieldApplier
{
    public function __construct(
        #[Autowire('%letkode.locale.default_locale%')]
        private string $defaultLocale,
    ) {
    }

    /**
     * Assigns a translatable field from a plain string (default locale only)
     * or a locale map (e.g. ['en' => 'Users', 'es' => 'Usuarios']).
     *
     * @param string|array<string, string> $value
     */
    public function apply(object $entity, string $field, string|array $value): void
    {
        if (\is_string($value)) {
            $entity->$field = $value;

            return;
        }

        $entity->$field = $value[$this->defaultLocale] ?? reset($value);

        foreach ($value as $locale => $translated) {
            $entity->setTranslation($locale, $field, $translated);
        }
    }
}
