<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class LetkodeLocaleBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('default_locale')->defaultValue('en')->end()
                ->arrayNode('supported_locales')
                    ->useAttributeAsKey('code')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $builder->setParameter('letkode.locale.default_locale', $config['default_locale']);
        $builder->setParameter('letkode.locale.supported_locales', $config['supported_locales']);
    }
}
