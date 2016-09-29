<?php

/**************************************************************************
 * ScriptHandler.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Composer;

use Incenteev\ParameterHandler\ScriptHandler;

/**
 * ScriptHandler.
 */
class ScriptHandler
{

    /**
     * Install the dory bundle.
     */
    public static function install()
    {
        echo "Install TACT Dory Bundle";

        $process = new ScriptHandlerProcess(ScriptHandlerModes::SIMPLE_INSTALLATION);

        $process->run();
    }

    /**
     * Update the dory bundle.
     */
    public static function update()
    {
        echo "Update TACT Dory Bundle";

        $process = new ScriptHandlerProcess(ScriptHandlerModes::SIMPLE_UPDATE);

        $process->run();
    }
}
