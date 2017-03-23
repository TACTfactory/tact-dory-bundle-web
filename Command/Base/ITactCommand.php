<?php

/**************************************************************************
 * ITactCommand.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 august. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Command\Base;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ITactCommand.
 *
 * Reprensents a simple command (DP), not a symfony console task.
 */
interface ITactCommand
{

    /**
     * Execute the command.
     *
     * @param ContainerInterface $container
     *            Container to get some data/services/...
     * @param OutputInterface|null $output
     *            Object where write potentials message (no send mesage if null).
     */
    public function execute(ContainerInterface $container, OutputInterface $output = null);
}
