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
     * The entity manager.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

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

    // Methods to override in children classes (DP template method).

//     /**
//      * Return the name of the targeted entity.
//      *
//      * @return string
//      */
//     abstract protected function getEntityName();

    /**
     * Generate then return an entity that
     */
    abstract protected function generateEntity();

    // Configurations methods.

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
        $this->id = 1;

        $this->entity = $this->generateEntity();
        $this->repository = $this->em->getRepository(get_class($this->entity));

        static::rebuildDatabase();
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
     * Runs 3 console commands: (all with -q and -e=test)
     * doctrine:schema:drop --force
     * doctrine:schema:create
     * doctrine:fixtures:load --no-interaction
     *
     * After successful database rebuild, it will copy it for further reuse
     */
    protected function rebuildDatabase() {
        $conn = static::$kernel->getContainer()->get('doctrine.dbal.default_connection');

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
            static::runConsole('doctrine:fixtures:load', array(
                '-n' => true
            ));

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
        $options['-e'] = $this->environment;
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
}
