<?php
/**
 * Created by PhpStorm.
 * User: iridian_dev5
 * Date: 12/05/2017
 * Time: 4:28 PM
 */

namespace PagosPayuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pagos_payu');

        $rootNode
            ->children()
            ->scalarNode('api_payu')->defaultValue(false)->end()
        ;

        return $treeBuilder;
    }
}