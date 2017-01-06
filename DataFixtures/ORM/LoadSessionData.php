<?php

/**************************************************************************
 * LoadSessionData.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s) : Mickael Gaillard <mickael.gaillard@tactfactory.com>
 *             Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : 5 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Buzz\Exception\InvalidArgumentException;
use Tact\DoryBundle\DataFixtures\ORM\Base\MainAbstractFixture;
use Tact\DoryBundle\Enum\DatabaseTypes;

class LoadSessionData extends MainAbstractFixture implements OrderedFixtureInterface
{

    /**
     * Flag of error to print in case of wrong sgbd alias.
     *
     * @var string
     */
    const ERROR_FLAG_DATABASE_ALIAS = 'Impossible to load session with "%s" as sgbd parameter into configuration.';

    const SESSION_SCHEMA_SQLITE = "CREATE TABLE IF NOT EXISTS sys_session (
        session_id varchar(255) NOT NULL,
        session_value BLOB NOT NULL,
        session_time int(11) NOT NULL,
        sess_lifetime INT NOT NULL,
        PRIMARY KEY (session_id)
        )";

    const SESSION_SCHEMA_MYSQL = self::SESSION_SCHEMA_SQLITE . " ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // IF NOT EXISTS need postgres > 9.5.
    const SESSION_SCHEMA_POSTGRESQL = "CREATE TABLE IF NOT EXISTS sys_session (
        session_id VARCHAR(128) NOT NULL PRIMARY KEY,
        session_value BYTEA NOT NULL,
        session_time INTEGER NOT NULL,
        sess_lifetime INTEGER NOT NULL
        );";

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(ObjectManager $manager) {
        $sgbd = $this->container->getParameter('database_type');

        if ($this->container->get('kernel')->getEnvironment() === 'test') {
            $query = self::SESSION_SCHEMA_SQLITE;
        } else {
            switch ($sgbd) {
                case DatabaseTypes::POSTGRES:
                    $query = static::SESSION_SCHEMA_POSTGRESQL;
                    break;
                case DatabaseTypes::MYSQL:
                    $query = static::SESSION_SCHEMA_MYSQL;
                    break;
                default:
                    $message = sprintf(static::ERROR_FLAG_DATABASE_ALIAS, $sgbd);
                    throw new InvalidArgumentException($message);
                    break;
            }
        }

        $manager->getConnection()->exec($query);

        $manager->flush();
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getOrder() {
        return 0;
    }
}
