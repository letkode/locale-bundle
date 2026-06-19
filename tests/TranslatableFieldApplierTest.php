<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\Tests;

use Letkode\LocaleBundle\TranslatableFieldApplier;
use PHPUnit\Framework\TestCase;

final class TranslatableFieldApplierTest extends TestCase
{
    private function makeEntity(): object
    {
        return new class {
            public string $name = '';
            public string|null $description = null;
            /** @var array<string, array<string, string>> */
            public array $translations = [];

            public function setTranslation(string $locale, string $field, string $value): void
            {
                $this->translations[$locale][$field] = $value;
            }
        };
    }

    public function testAppliesStringValueDirectly(): void
    {
        $entity = $this->makeEntity();
        $applier = new TranslatableFieldApplier('en');

        $applier->apply($entity, 'name', 'Product');

        self::assertSame('Product', $entity->name);
        self::assertEmpty($entity->translations);
    }

    public function testAppliesDefaultLocaleFromMap(): void
    {
        $entity = $this->makeEntity();
        $applier = new TranslatableFieldApplier('en');

        $applier->apply($entity, 'name', ['en' => 'Module', 'es' => 'Módulo']);

        self::assertSame('Module', $entity->name);
    }

    public function testStoresAllTranslationsFromMap(): void
    {
        $entity = $this->makeEntity();
        $applier = new TranslatableFieldApplier('en');

        $applier->apply($entity, 'name', ['en' => 'Module', 'es' => 'Módulo']);

        self::assertSame('Module', $entity->translations['en']['name']);
        self::assertSame('Módulo', $entity->translations['es']['name']);
    }

    public function testFallsBackToFirstValueWhenDefaultLocaleMissing(): void
    {
        $entity = $this->makeEntity();
        $applier = new TranslatableFieldApplier('en');

        $applier->apply($entity, 'name', ['es' => 'Módulo', 'fr' => 'Module']);

        self::assertSame('Módulo', $entity->name);
    }

    public function testAppliesMultipleFieldsIndependently(): void
    {
        $entity = $this->makeEntity();
        $applier = new TranslatableFieldApplier('en');

        $applier->apply($entity, 'name', ['en' => 'Users', 'es' => 'Usuarios']);
        $applier->apply($entity, 'description', ['en' => 'Manage users', 'es' => 'Gestionar usuarios']);

        self::assertSame('Users', $entity->name);
        self::assertSame('Manage users', $entity->description);
        self::assertSame('Usuarios', $entity->translations['es']['name']);
        self::assertSame('Gestionar usuarios', $entity->translations['es']['description']);
    }
}
