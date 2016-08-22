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
             '$bundles, $this->getEnvironment());' . self::ENDL . self::ENDL . self::TAB . self::TAB .
             self::APP_KERNEL_OLD_VALUE;

    const ROUTING_CONTENT = "\r\ndory: \r\n" . self::TAB .
             "resource: \"@TactDoryBundle/Resources/config/routing.yml\"\r\n" . self::TAB . "prefix: /\r\n";

    /**
     * The parameters to add at dist.
     *
     * @var array
     */
    private static $requiredParameters = [
        'database_driver' => 'pdo_mysql',
        'database_type' => 'mysql',
        'router.request_context.host' => 'example.com',
        'router.request_context.scheme' => 'http',
        'router.request_context.base_url' => ''
    ];

    /*
     * Method used only for tests.
     */
    private static function tempTest()
    {
        $content = '# This file is auto-generated during the dory install' . self::ENDL . 'parameters:' . self::ENDL .
                 self::TAB . 'database_driver: pdo_mysql' . self::ENDL . self::TAB . 'database_type: mysql' . self::ENDL .
                 self::TAB . 'database_host: 127.0.0.1' . self::ENDL . self::TAB . 'database_port: 3306' . self::ENDL .
                 self::TAB . 'database_name: symfony' . self::ENDL . self::TAB . 'database_user: root' . self::ENDL .
                 self::TAB . 'database_password: root' . self::ENDL . self::TAB . 'mailer_transport: smtp' . self::ENDL .
                 self::TAB . 'mailer_host: 127.0.0.1' . self::ENDL . self::TAB . 'mailer_user: null' . self::ENDL .
                 self::TAB . 'mailer_password: null' . self::ENDL . self::TAB . 'locale: en' . self::ENDL . self::TAB .
                 'secret: ThisTokenIsNotSoSecretChangeIt';

        // file_put_contents(sprintf('%s/%s', self::PROJECT_ROOT_PATH, 'app/config/parameters.yml'), $content);
    }

    /**
     * Install the dory bundle.
     */
    public static function install()
    {
        echo "Install TACT Dory Bundle";

        self::copyScripts();
        self::addRouting();
        self::updateAppKernel();
        self::updateConfig();
        self::updateParameterDist();
    }

    /**
     * Generate the ant file.
     */
    private static function copyScripts()
    {
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
    private static function addRouting()
    {
        if (strpos(file_get_contents(self::ROUTING_DESTINATION_PATH), 'TactDoryBundle') == false) {
            file_put_contents(self::ROUTING_DESTINATION_PATH, self::ROUTING_CONTENT, FILE_APPEND);
        }
    }

    /**
     * Add our bundles into AppKernel.
     */
    private static function updateAppKernel()
    {
        $filepath = self::APP_KERNEL_DESTINATION_PATH;
        $content = file_get_contents($filepath);

        if (strpos($content, 'DoryBundle') == false) {
            $content = str_replace(self::APP_KERNEL_OLD_VALUE, self::APP_KERNEL_NEW_VALUE, $content);

            file_put_contents($filepath, $content);
        }
    }

    /**
     * Update the config file to add default
     */
    private static function updateConfig()
    {
        $filepath = sprintf('%s/%s', self::PROJECT_ROOT_PATH, 'app/config/config.yml');
        $content = file_get_contents($filepath);

        if (strpos($content, 'DoryBundle') == false) {
            $newValue = 'imports:' . self::ENDL . self::TAB .
                     '- { resource: "@TactDoryBundle/Resources/config/config.yml" } # Not loaded by Dory extention due to needed execution order.';

            if (strpos($content, 'imports:')) {
                $content = str_replace('imports:', $newValue, $content);
            } else {
                $content = $newValue . self::ENDL . $content;
            }

            file_put_contents($filepath, $content);
        }
    }

    /**
     * Update parameters.dist file to add the necessary parameters.
     */
    private static function updateParameterDist()
    {
        $modified = false;
        $filepath = sprintf('%s/%s', self::PROJECT_ROOT_PATH, 'app/config/parameters.yml.dist');
        $content = file_get_contents($filepath);

        foreach (self::$requiredParameters as $parameter => $defaultValue) {
            if (strpos($content, $parameter)) {
                $newParameter = sprintf('    %s: %s', $parameter, $defaultValue);

                $modified = true;
            }
        }

        if ($modified === true) {
            dump(sprintf("\r\n\r\nNew content (of '%s'):\r\n%s\r\n\r\n", $filepath, $content));

            file_put_contents($filepath, $content);
        }
    }
}
