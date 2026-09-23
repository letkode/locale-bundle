<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Provider;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class LocaleProvider
{
    public function __construct(
        private RequestStack $requestStack,
        #[Autowire('%letkode.locale.default_locale%')]
        private string $defaultLocale,
    ) {
    }

    public function get(): string
    {
        return $this->requestStack->getCurrentRequest()?->getLocale() ?? $this->defaultLocale;
    }
}
