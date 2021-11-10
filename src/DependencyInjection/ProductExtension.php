<?php

namespace MartenaSoft\WarehouseProduct\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ProductExtension extends Extension
{
    public function prepend(ContainerBuilder $container): void
    {
        //TODO: do preload extension
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $config['projectDir'] = $container->getParameter('kernel.project_dir');
        $container->setParameter(ProductBundle::getConfigName(), $config);
        $container->setParameter('twig.globals', $config);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('services.yaml');
    }
}