<?php

/**************************************************************************
 * ScriptHandler.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 8 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Composer;

/**
 * ScriptHandler.
 */
class ScriptHandler
{

    const TAB = '    ';

    const ENDL = "\r\n";

    const DORY_ROOT_PATH = __DIR__ . '/..';

    const PROJECT_ROOT_PATH = self::DORY_ROOT_PATH . '/../../..';

    const SCRIPT_DIRECTORY = self::DORY_ROOT_PATH . '/scripts_to_copy';

    const ROUTING_DESTINATION_PATH = self::PROJECT_ROOT_PATH . '/app/config/routing.yml';

    const APP_KERNEL_DESTINATION_PATH = self::PROJECT_ROOT_PATH . '/app/AppKernel.php';

    const APP_KERNEL_OLD_VALUE = 'return $bundles';

    const APP_KERNEL_NEW_VALUE = '$bundles = \Tact\DoryBundle\Utils\KernelImporter::mergeLocalBundles(' .
             '$bundles, $this->getEnvironment());' . self::ENDL . self::ENDL . self::TAB . self::TAB . self::APP_KERNEL_OLD_VALUE;

    const ROUTING_CONTENT = "\r\ndory: \r\n" . self::TAB .
             "resource: \"@TactDoryBundle/Resources/config/routing.yml\"\r\n" . self::TAB . "prefix: /\r\n";

    /**
     * Install the dory bundle.
     */
    public static function install() {
        echo "Install TACT Dory Bundle";

        self::copyScripts();
        self::addRouting();
        self::updateAppKernel();
    }

    /**
     * Generate the ant file.
     */
    private static function copyScripts() {
        foreach (scandir(self::SCRIPT_DIRECTORY) as $script) {
            $source = sprintf('%s/%s', self::SCRIPT_DIRECTORY, $script);
            $destination = sprintf('%s/%s', self::PROJECT_ROOT_PATH, $script);

            if (file_exists($destination) == false) { // Test if first run.
                copy($source, $destination);
            }
        }
    }

    /**
     * Add the routing dependencies.
     */
    private static function addRouting() {
        if (strpos(file_get_contents(self::ROUTING_DESTINATION_PATH), 'TactDoryBundle') == false) {
            file_put_contents(self::ROUTING_DESTINATION_PATH, self::ROUTING_CONTENT, FILE_APPEND);
        }
    }

    /**
     * Add our bundles into AppKernel.
     */
    private static function updateAppKernel() {
        $filepath = self::APP_KERNEL_DESTINATION_PATH;

        if (strpos(file_get_contents($filepath), 'DoryBundle') == false) {
            $content = file_get_contents($filepath);

            $content = str_replace(self::APP_KERNEL_OLD_VALUE, self::APP_KERNEL_NEW_VALUE, $content);

            file_put_contents($filepath, $content);
        }
    }
}
