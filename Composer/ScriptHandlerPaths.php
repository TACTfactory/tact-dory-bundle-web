<?php

/**************************************************************************
 * ScriptHandlerPaths.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 28 sept. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Composer;

/**
 * Enumerate the paths used for the script handler.
 */
abstract class ScriptHandlerPaths
{

    const DORY_ROOT_PATH = __DIR__ . '/..';

    const PROJECT_ROOT_PATH = self::DORY_ROOT_PATH . '/../../..';

    const SCRIPT_DIRECTORY = self::DORY_ROOT_PATH . '/scripts_to_copy';

    const CONFIG_OVERRIDES_DIRECTORY = self::DORY_ROOT_PATH . '/Resources/files/ConfigOverride';

    const PROJECT_CONF_PATH = self::PROJECT_ROOT_PATH . '/app/config';

    const ROUTING_DESTINATION_PATH = self::PROJECT_CONF_PATH . '/routing.yml';

    const APP_KERNEL_DESTINATION_PATH = self::PROJECT_ROOT_PATH . '/app/AppKernel.php';
}
