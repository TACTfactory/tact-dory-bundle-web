<?php

/**************************************************************************
 * AbstractTactTest.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : Aug 17, 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Tests\Base;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * AbstractTactTest.
 */
abstract class AbstractTactTest extends WebTestCase
{

    /**
     * The default id for database/functionnals tests.
     *
     * @var integer
     */
    const DEFAULT_ID = 1;

    /**
     * The entity manager.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * The entity repository.
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * The entity.
     *
     * @var mixed
     */
    protected $entity;

    /**
     * The id.
     *
     * @var integer
     */
    protected $id;

    protected static $application;

    protected static $debug = false;

    protected static $fixturesSuffix = '.fixtures';

    protected static $fixturesPath = '';

    /**
     * Generates then returns an entity which be used for crud tests and to get repository.
     *
     * @return An entity class.
     */
    abstract protected function generateEntity();

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function setUp() {
        parent::setup();

        self::bootKernel();

        // Init Entity Manager
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->id = self::DEFAULT_ID;

        // Generates a simple entity for "crud" test.
        $this->entity = $this->generateEntity();

        // Get repository if we have generate an entity.
        if ($this->entity !== null) {
            $this->repository = $this->em->getRepository(get_class($this->entity));
        }
    }

    /**
     * It will run before any setUps and tests in given test suite
     * This hook will drop current schema, creat schema and load fixtures
     * then it will create a copy of the databse, so it will be used in the future tests in this suite
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        self::bootKernel();
        static::bootstrapApplication();
    }

    /**
     * After all tests in given test suite it will remove database copy
     * Because of this next test suite needs to create its own
     */
    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
    }

    /**
     * Runs many console commands: (all with -q and -e=test)
     * doctrine:schema:drop --force
     * doctrine:schema:create
     * doctrine:fixtures:load --no-interaction
     *
     * After successful database rebuild, it will copy it for further reuse
     */
    protected function rebuildDatabase() {
        $fixturesInitialized = false;
        $conn = static::$kernel->getContainer()->get('doctrine.dbal.default_connection');
        $bundles = self::$kernel->getBundles();

        $dbPath = $conn->getDatabase();
        static::$fixturesPath = $dbPath . static::$fixturesSuffix;

        if (! file_exists(static::$fixturesPath)) {
            // create fresh database (schema and fixtures)
            static::runConsole('doctrine:database:create', array(
                '-n' => true
            ));
            static::runConsole('doctrine:schema:drop', array(
                '--force' => true
            ));
            static::runConsole('doctrine:schema:create', array());

            if (array_key_exists('DoctrineFixturesBundle', $bundles)) {
                static::runConsole('doctrine:fixtures:load', array(
                    '-q' => true,
                    '-n' => true,
                    '-e' => 'test',
                    '--append' => $fixturesInitialized
                ));

                $fixturesInitialized = true;
            }

            if (array_key_exists('NelmioAliceBundle', $bundles)) {
                static::runConsole('hautelook:fixtures:load', array(
                    '-n' => true,
                    '-e' => 'dev',
                    '--append' => $fixturesInitialized
                ));

                $fixturesInitialized = true;
            }

            // copy fresh database to be reused in the future
            copy($dbPath, static::$fixturesPath);
        } else {
            // copy fixtures database to doctrine location
            copy(static::$fixturesPath, $dbPath);
        }
    }

    /**
     * Bootstraps console application.
     * It's needed to run commands from the code
     */
    protected static function bootstrapApplication() {
        static::$application = new Application(static::$kernel);
        static::$application->setAutoExit(false);
    }

    /**
     * It always run with given environment and in quiet mode (no output on the console)
     */
    protected function runConsole($command, array $options = array()) {
        $options['-e'] = $options['-e'] ?? $this->environment;
        $options['-q'] = null;

        $input = new ArrayInput(array_merge($options, array(
            'command' => $command
        )));
        $result = self::$application->run($input);

        if (0 != $result) {
            throw new \RuntimeException(
                    sprintf('Something has gone wrong, got return code %d for command %s', $result, $command));
        }

        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }

    /**
     * Gets the entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getManager()
    {
        return $this->em;
    }
}
