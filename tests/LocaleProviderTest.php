<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Tests;

use Letkode\LocaleBundle\LocaleProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleProviderTest extends TestCase
{
    public function testReturnsLocaleFromCurrentRequest(): void
    {
        $request = new Request();
        $request->setLocale('es');

        $stack = new RequestStack();
        $stack->push($request);

        $provider = new LocaleProvider($stack, 'en');

        self::assertSame('es', $provider->get());
    }

    public function testFallsBackToDefaultLocaleWhenNoRequest(): void
    {
        $stack = new RequestStack();
        $provider = new LocaleProvider($stack, 'en');

        self::assertSame('en', $provider->get());
    }

    public function testUsesConfiguredDefaultLocale(): void
    {
        $stack = new RequestStack();
        $provider = new LocaleProvider($stack, 'fr');

        self::assertSame('fr', $provider->get());
    }

    public function testUsesRequestLocaleOverDefault(): void
    {
        $request = new Request();
        $request->setLocale('pt');

        $stack = new RequestStack();
        $stack->push($request);

        $provider = new LocaleProvider($stack, 'en');

        self::assertSame('pt', $provider->get());
    }
}
