<?php

/**************************************************************************
 * BaseSymfonyCommand.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Command\Base;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * BaseSymfonyCommand.
 *
 * Add utils|proxies methods to simplify/generalize several uses and choices.
 */
abstract class BaseSymfonyCommand extends ContainerAwareCommand
{

    /**
     * Get a symfony service.
     *
     * @param string $serviceName
     *
     * @return mixed The requested service.
     */
    protected function getService($serviceName) {
        return $this->getContainer()->get($serviceName);
    }

    /**
     * Define which manager we use for commands.
     *
     * @return ObjectManager|EntityManagerInterface
     */
    protected function getManager() {
        return $this->getService('doctrine')->getManager();
    }

    /**
     * Get an entity repository.
     *
     * @param string $repositoryName
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($repositoryName) {
        return $this->getManager()->getRepository($repositoryName);
    }
}
