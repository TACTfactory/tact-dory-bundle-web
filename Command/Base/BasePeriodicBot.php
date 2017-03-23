<?php

/**************************************************************************
 * BasePeriodicBot.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Command\Base;

/**
 * BasePeriodicBot.
 */
abstract class BasePeriodicBot extends AbstractMultipleCommand
{

    const NAME_PREFIX = 'tact:bot:';

    const FLAG_DEFAULT_DESCRIPTION = 'Do %s processes (not make to be call manually, see the wiki documentation).';

    abstract protected function getPeriodicityName();

    /**
     *
     * {@inheritdoc}
     *
     */
    final protected function configure()
    {
        $periodicityLabel = $this->getPeriodicityName();

        $this->setName(self::NAME_PREFIX . $periodicityLabel);
        $this->setDescription(sprintf(self::FLAG_DEFAULT_DESCRIPTION, $periodicityLabel));
    }
}
