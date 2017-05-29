<?php

/**************************************************************************
 * AbstractMultipleCommand.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Command\Base;

use Tact\DoryBundle\Command\Base\BaseSymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AbstractMultipleCommand.
 * @deprecated Will be deleted in one future version.
 */
abstract class AbstractMultipleCommand extends BaseSymfonyCommand
{

    /**
     * Get the list of command to execute.
     *
     * @return ITactCommand[]
     */
    abstract protected function getCommands();

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        foreach ($this->getCommands() as $command) {
            if ($command instanceof ITactCommand) {
                $command->execute($container, $output);
            } else {
                throw new \Exception('Your command must to implement ITactCommand to be called by MultipleCommand');
            }
        }
    }
}
