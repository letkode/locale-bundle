<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Tests\EventListener;

use Letkode\LocaleBundle\EventListener\LocaleListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class LocaleListenerTest extends TestCase
{
    private const SUPPORTED = ['en' => 'English', 'es' => 'Español'];

    private function makeEvent(Request $request, bool $isMain = true): RequestEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $type = $isMain ? HttpKernelInterface::MAIN_REQUEST : HttpKernelInterface::SUB_REQUEST;

        return new RequestEvent($kernel, $request, $type);
    }

    public function testSetsLocaleFromAcceptLanguageHeader(): void
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'es-ES,es;q=0.9,en;q=0.8']);
        $listener = new LocaleListener('en', self::SUPPORTED);

        $listener->onKernelRequest($this->makeEvent($request));

        self::assertSame('es', $request->getLocale());
    }

    public function testFallsBackToDefaultWhenLocaleNotSupported(): void
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr,de;q=0.9']);
        $listener = new LocaleListener('en', self::SUPPORTED);

        $listener->onKernelRequest($this->makeEvent($request));

        self::assertSame('en', $request->getLocale());
    }

    public function testIgnoresSubRequests(): void
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'es']);
        $listener = new LocaleListener('en', self::SUPPORTED);

        $listener->onKernelRequest($this->makeEvent($request, isMain: false));

        // Sub-request must not change the locale — stays at Symfony default
        self::assertSame('en', $request->getLocale());
    }

    public function testSetsEnglishWhenNoAcceptLanguageHeader(): void
    {
        $request = new Request();
        $listener = new LocaleListener('en', self::SUPPORTED);

        $listener->onKernelRequest($this->makeEvent($request));

        self::assertSame('en', $request->getLocale());
    }
}
