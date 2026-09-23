<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Tests\Trait;

use Letkode\LocaleBundle\Trait\HasTranslationsTrait;
use PHPUnit\Framework\TestCase;

final class HasTranslationsTraitTest extends TestCase
{
    private function makeEntity(): object
    {
        return new class {
            use HasTranslationsTrait;
        };
    }

    public function testGetTranslationReturnsNullWhenUnset(): void
    {
        $entity = $this->makeEntity();

        self::assertNull($entity->getTranslation('es', 'name'));
    }

    public function testSetTranslationStoresValuePerLocaleAndField(): void
    {
        $entity = $this->makeEntity();

        $entity->setTranslation('es', 'name', 'Producto');
        $entity->setTranslation('en', 'name', 'Product');

        self::assertSame('Producto', $entity->getTranslation('es', 'name'));
        self::assertSame('Product', $entity->getTranslation('en', 'name'));
    }

    public function testSetTranslationWithNullValueRemovesIt(): void
    {
        $entity = $this->makeEntity();
        $entity->setTranslation('es', 'name', 'Producto');

        $entity->setTranslation('es', 'name', null);

        self::assertNull($entity->getTranslation('es', 'name'));
    }
}
