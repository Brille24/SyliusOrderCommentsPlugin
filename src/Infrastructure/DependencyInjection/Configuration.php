<?php

namespace Sylius\OrderCommentsPlugin\Infrastructure\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sylius_order_comments');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
