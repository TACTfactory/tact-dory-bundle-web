<?php

/**************************************************************************
 * ScriptHandlerProcess.php, TACT Dory
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
 * ScriptHandlerProcess.
 */
class ScriptHandlerProcess
{

    const TAB = '    ';

    const ENDL = "\r\n";

    const APP_KERNEL_OLD_VALUE = 'return $bundles';

    const APP_KERNEL_NEW_VALUE = '$bundles = \Tact\DoryBundle\Utils\KernelImporter::mergeLocalBundles(' .
             '$bundles, $this->getEnvironment());' . self::ENDL . self::ENDL . self::TAB . self::TAB .
             self::APP_KERNEL_OLD_VALUE;

    const ROUTING_CONTENT = "\r\ndory: \r\n" . self::TAB .
             "resource: \"@TactDoryBundle/Resources/config/routing.yml\"\r\n" . self::TAB . "prefix: /\r\n";

    const CONFIG_IMPORTS = '- { resource: "imports.yml" }';

    const FLAG_CONFIG_IMPORT_RESOURCE = ' - { resource: "%s" }';

    /**
     * The execution mode.
     *
     * @var integer
     */
    protected $mode;

    /**
     * Construct.
     *
     * @param boolean $installation
     *            Tell if the current process is an installation, if it is not then this is umpdate.
     */
    public function ScriptHandlerProcess(int $mode)
    {
        $this->mode = $mode;
    }

    /**
     * Get the mode.
     *
     * @return integer
     */
    public function getMode() : int
    {
        return $this->mode;
    }

    /**
     * Set the mode.
     *
     * @param integer $mode
     *
     * @return ScriptHandlerProcess
     */
    protected function setMode(int $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Test is process is installation.
     *
     * @return boolean
     */
    public function isInstallation() : bool
    {
        return $this->mode === ScriptHandlerModes::SIMPLE_INSTALLATION;
    }

    /**
     * Test is process is update.
     *
     * @return boolean
     */
    public function isUpdate() : bool
    {
        return $this->mode === ScriptHandlerModes::SIMPLE_UPDATE;
    }

    /**
     * Run the process.
     *
     * Adapted by mode.
     */
    public function run()
    {
        $this->copyScripts();
        $this->addRouting();
        $this->updateAppKernel();
        $this->updateConfig();
        $this->updateParameterDist();
    }

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
    private function tempTest()
    {
        $content = '# This file is auto-generated during the dory install' . self::ENDL . 'parameters:' . self::ENDL .
        self::TAB . 'database_driver: pdo_mysql' . self::ENDL . self::TAB . 'database_type: mysql' . self::ENDL .
        self::TAB . 'database_host: 127.0.0.1' . self::ENDL . self::TAB . 'database_port: 3306' . self::ENDL .
        self::TAB . 'database_name: symfony' . self::ENDL . self::TAB . 'database_user: root' . self::ENDL .
        self::TAB . 'database_password: root' . self::ENDL . self::TAB . 'mailer_transport: smtp' . self::ENDL .
        self::TAB . 'mailer_host: 127.0.0.1' . self::ENDL . self::TAB . 'mailer_user: null' . self::ENDL .
        self::TAB . 'mailer_password: null' . self::ENDL . self::TAB . 'locale: en' . self::ENDL . self::TAB .
        'secret: ThisTokenIsNotSoSecretChangeIt';

        // file_put_contents(sprintf('%s/%s', ScriptHandlerPaths::PROJECT_ROOT_PATH, 'app/config/parameters.yml'), $content);
    }

    /**
     * Generate the ant file.
     */
    private function copyScripts()
    {
        foreach (scandir(ScriptHandlerPaths::SCRIPT_DIRECTORY) as $script) {
            $source = sprintf('%s/%s', ScriptHandlerPaths::SCRIPT_DIRECTORY, $script);
            $destination = sprintf('%s/%s', ScriptHandlerPaths::PROJECT_ROOT_PATH, $script);

            if (file_exists($destination) == false) { // Test if first run.
                copy($source, $destination);
            }
        }
    }

    /**
     * Add the routing dependencies.
     */
    private function addRouting()
    {
        if (strpos(file_get_contents(ScriptHandlerPaths::ROUTING_DESTINATION_PATH), 'TactDoryBundle') == false) {
            file_put_contents(ScriptHandlerPaths::ROUTING_DESTINATION_PATH, self::ROUTING_CONTENT, FILE_APPEND);
        }
    }

    /**
     * Add our bundles into AppKernel.
     */
    private function updateAppKernel()
    {
        $filepath = ScriptHandlerPaths::APP_KERNEL_DESTINATION_PATH;
        $content = file_get_contents($filepath);

        if (strpos($content, 'DoryBundle') == false) {
            $content = str_replace(self::APP_KERNEL_OLD_VALUE, self::APP_KERNEL_NEW_VALUE, $content);

            file_put_contents($filepath, $content);
        }
    }

    /**
     * Update the config file to add default
     */
    private function updateConfig()
    {
        $this->updateConfigImports('config', 'imports.yml');
        $this->updateConfigImports('config_dev', '@TactDoryBundle/Resources/config/config_dev.yml');
        $this->updateConfigImports('config_test', '@TactDoryBundle/Resources/config/config_test.yml');
        $this->updateConfigImports('config_prod', '@TactDoryBundle/Resources/config/config_prod.yml');

        { // Check that files for overrides are generated.
            foreach (scandir(ScriptHandlerPaths::CONFIG_OVERRIDES_DIRECTORY) as $configFile) {
                $source = sprintf('%s/%s', ScriptHandlerPaths::CONFIG_OVERRIDES_DIRECTORY, $configFile);
                $destination = sprintf('%s/%s', ScriptHandlerPaths::PROJECT_CONF_PATH, $configFile);

                if (file_exists($destination) == false || preg_match('/imports(?:_\w+)?\.yml$/', $destination)) {
                    copy($source, $destination);
                }
            }
        }
    }

    /**
     * Update the dory importation for a dependency.
     *
     * @param string $filenameWithoutExtension
     * @param string $toImport
     */
    private function updateConfigImports(string $filenameWithoutExtension, string $toImport)
    {
        $importationLine = sprintf(self::FLAG_CONFIG_IMPORT_RESOURCE, $toImport);
        $filepath = sprintf('%s/app/config/%s.yml', ScriptHandlerPaths::PROJECT_ROOT_PATH, $filenameWithoutExtension);
        $content = file_get_contents($filepath);
        $flag = sprintf('#%s#', str_replace(' ', '\s*', $importationLine));

        if (preg_match($flag, $content) == false) {
            $sentence = sprintf('%s ## Don\'t modify this import.', $importationLine);
            $newValue = 'imports:' . self::ENDL . self::TAB . $sentence;

            if (preg_match('/\bimports\s*:/', $content)) {
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
    private function updateParameterDist()
    {
        $modified = false;
        $filepath = sprintf('%s/%s', ScriptHandlerPaths::PROJECT_ROOT_PATH, 'app/config/parameters.yml.dist');
        $content = file_get_contents($filepath);
        $parameterTarget = 'parameters:';

        foreach (self::$requiredParameters as $parameter => $defaultValue) {
            if (strpos($content, $parameter) == false) {
                $toAdd = sprintf("%s%s    %s: %s", $parameterTarget, self::ENDL, $parameter, $defaultValue);

                $content = str_replace($parameterTarget, $toAdd, $content);

                $modified = true;
            }
        }

        if ($modified === true) {
            file_put_contents($filepath, $content);
        }
    }
}
