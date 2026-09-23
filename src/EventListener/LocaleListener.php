<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class LocaleListener
{
    public function __construct(
        #[Autowire('%letkode.locale.default_locale%')]
        private string $defaultLocale,
        #[Autowire('%letkode.locale.supported_locales%')]
        private array $supportedLocales,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $locale = $event->getRequest()->getPreferredLanguage(array_keys($this->supportedLocales))
            ?: $this->defaultLocale;

        $event->getRequest()->setLocale($locale);
    }
}
