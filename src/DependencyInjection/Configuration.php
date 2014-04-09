<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package ThinFrame\Twig\DependencyInjection
 * @since   0.2
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('twig');


        $rootNode
            ->children()
            ->arrayNode('error_pages')->isRequired()
            ->prototype('array')->children()
            ->scalarNode('code')->isRequired()->end()
            ->scalarNode('view')->isRequired()->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
