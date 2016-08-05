<?php

/**************************************************************************
 * TactDoryExtension.php, Tact Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 3 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class TactDoryExtension extends Extension implements PrependExtensionInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function prepend(ContainerBuilder $container) {
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.yml');
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
