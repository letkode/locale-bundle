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
                ->scalarNode('default_locale')
                    ->defaultValue('en')
                    ->info('Locale used when Accept-Language matches none of the supported locales.')
                ->end()
                ->variableNode('supported_locales')
                    ->defaultValue([])
                    ->info('Accepted locales as {code: label}. Empty accepts whatever Accept-Language asks for.')
                    ->example(['en' => 'English', 'es' => 'Español'])
                ->end()
                ->integerNode('listener_priority')
                    ->defaultValue(15)
                    ->info('Priority of the kernel.request listener that sets the locale.')
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $builder->setParameter('letkode.locale.default_locale', $config['default_locale']);
        $builder->setParameter('letkode.locale.supported_locales', $config['supported_locales']);
        $builder->setParameter('letkode.locale.listener_priority', $config['listener_priority']);
    }
}
