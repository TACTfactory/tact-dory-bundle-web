<?php

/**************************************************************************
 * TactDoryExtension.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 3 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $container->setParameter('tact_dory.session', $processedConfig['session']);

//         $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
//         $loader->load('services.yml');
    }
}
