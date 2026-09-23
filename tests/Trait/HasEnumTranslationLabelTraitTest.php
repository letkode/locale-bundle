<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Tests\Trait;

use Letkode\LocaleBundle\Trait\HasEnumTranslationLabelTrait;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AllowMockObjectsWithoutExpectations]
final class HasEnumTranslationLabelTraitTest extends TestCase
{
    public function testGetLabelTranslatesUsingTheEnumsDomain(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->once())
            ->method('trans')
            ->with('fixture_status.active', [], 'fixture_status', null)
            ->willReturn('Active');

        self::assertSame('Active', FixtureStatusEnum::ACTIVE->getLabel($translator));
    }

    public function testGetTranslationsResolvesOneLabelPerLocale(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturnMap([
            ['fixture_status.active', [], 'fixture_status', 'en', 'Active'],
            ['fixture_status.active', [], 'fixture_status', 'es', 'Activo'],
        ]);

        $result = FixtureStatusEnum::ACTIVE->getTranslations($translator, ['en', 'es']);

        self::assertSame(['en' => ['label' => 'Active'], 'es' => ['label' => 'Activo']], $result);
    }
}

enum FixtureStatusEnum: string
{
    use HasEnumTranslationLabelTrait;

    case ACTIVE = 'active';

    private static function translationDomain(): string
    {
        return 'fixture_status';
    }
}
