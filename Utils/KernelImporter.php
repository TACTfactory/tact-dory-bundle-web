<?php

/**************************************************************************
 * KernelImporter.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Utils;

use Tact\DoryBundle\TactDoryBundle;

/**
 * KernelImporter.
 */
abstract class KernelImporter
{

    const KERNEL_ENVIRONMENT_DEV = 'dev';

    const KERNEL_ENVIRONMENT_TEST = 'test';

    const KERNEL_ENVIRONMENT_PROD = 'prod';

    /**
     * Get the bundle list that we should add.
     *
     * @param string $environment
     *            The environment (env|prod|test).
     *
     * @return array
     */
    public static function getBundlesToAdd($environment) {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // User Manager section
            new \FOS\UserBundle\FOSUserBundle(),

            // REST section
            new \FOS\RestBundle\FOSRestBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            // Admin section
            new \Sonata\CoreBundle\SonataCoreBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),
            new \Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new \Sonata\AdminBundle\SonataAdminBundle(),
            new \Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new \Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new \Sonata\IntlBundle\SonataIntlBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),

            // Database section
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            // Datagrid section
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new \Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),

            // Maintenance.
            new  \Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),

            // File manager section
            new \Vich\UploaderBundle\VichUploaderBundle(), // File manager
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(), // Storage backend
            new \Liip\ImagineBundle\LiipImagineBundle(), // Image manipulation

            // Extra section
            new \Ornicar\GravatarBundle\OrnicarGravatarBundle(), // Gravatar

            new TactDoryBundle()
        ];

        if ($environment != self::KERNEL_ENVIRONMENT_PROD) {
            $bundles = array_merge($bundles,
                    [
                        new \Symfony\Bundle\DebugBundle\DebugBundle(),
                        new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
                        new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
                        new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
                        new \Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
                        // new \CoreSphere\ConsoleBundle\CoreSphereConsoleBundle(),
                        new \Liip\FunctionalTestBundle\LiipFunctionalTestBundle()
                    ]);
        }

        return $bundles;
    }

    /**
     * Return a list without several objects of the same class.
     *
     * @param Object[] $list
     *
     * @return Object[]
     */
    private static function preventDoubloon($list) {
        $found = [];
        $result = [];

        foreach ($list as $key => $element) {
            $class = get_class($element);

            if (in_array($class, $found) === false) {
                $found[] = $class;
                $result[] = $element;
            }
        }

        return $result;
    }

    /**
     * Merge necessary bundles with bundles to use Dory bundle.
     *
     * @param array $localBundles
     * @param string $environment
     *            The environment (env|prod|test).
     *
     * @return array
     */
    public static function mergeLocalBundles(array $localBundles, $environment) {
        $result = self::getBundlesToAdd($environment);

        $result = array_merge($result, $localBundles);

        return self::preventDoubloon($result);
    }
}
