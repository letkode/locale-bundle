<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class LocaleListener implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%letkode.locale.default_locale%')]
        private string $defaultLocale,
        #[Autowire('%letkode.locale.supported_locales%')]
        private array $supportedLocales,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 15],
        ];
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
