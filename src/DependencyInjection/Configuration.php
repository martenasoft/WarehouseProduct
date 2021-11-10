<?php

namespace MartenaSoft\WarehouseProduct\DependencyInjection;
use MartenaSoft\WarehouseProduct\ProductBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(ProductBundle::getConfigName());

        return $treeBuilder;
    }
}