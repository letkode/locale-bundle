<?php

declare(strict_types=1);

namespace Letkode\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('letkode_locale');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->scalarNode('default_locale')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('The default locale to use when none is resolved from the request.')
                ->end()
                ->arrayNode('supported_locales')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->info('Map of supported locales keyed by locale code (e.g. { en: English, es: Español }).')
                    ->scalarPrototype()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
