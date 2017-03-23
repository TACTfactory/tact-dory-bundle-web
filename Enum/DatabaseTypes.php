<?php

/**************************************************************************
 * DatabaseTypes.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 4 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Enum;

use Tact\DoryBundle\Enum\Base\TactEnumBase;

/**
 * DatabaseDrivers.
 */
abstract class DatabaseTypes extends TactEnumBase
{

    /**
     * Postgres sgbd alias.
     *
     * @var string
     */
    const POSTGRES = 'pgsql';

    /**
     * Mysql sgbd alias.
     *
     * @var string
     */
    const MYSQL = 'mysql';


    /**
     * SQL Lite sgbd alias.
     *
     * @var string
     */
    const SQLITE = 'sqlite';
}
