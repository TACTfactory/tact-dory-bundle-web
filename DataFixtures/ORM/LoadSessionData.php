<?php

/**************************************************************************
 * LoadNodeData.php, CmDB
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s) : Mickael Gaillard <mickael.gaillard@tactfactory.com>
 *             Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : Jan 25, 2016
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

    private $session_shema_mysql = "CREATE TABLE sys_session (
        session_id varchar(255) NOT NULL,
        session_value BLOB NOT NULL,
        session_time int(11) NOT NULL,
        sess_lifetime INT NOT NULL,
        PRIMARY KEY (session_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    private $session_shema_postgresql = "CREATE TABLE sys_session (
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

        switch ($sgbd) {
            case DatabaseTypes::POSTGRES:
                $query = $this->session_shema_postgresql;
                break;
            case DatabaseTypes::MYSQL:
                $query = $this->session_shema_mysql;
                break;
            default:
                $message = sprintf(self::ERROR_FLAG_DATABASE_ALIAS, $sgbd);
                throw new InvalidArgumentException($message);
                break;
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
