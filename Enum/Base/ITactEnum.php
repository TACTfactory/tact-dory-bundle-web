<?php

/**************************************************************************
 * ITactEnum.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Enum\Base;

/**
 * ITactEnum.
 */
interface ITactEnum
{

    /**
     * Get the constants declared on child.
     *
     * The internal values.
     *
     * @return array
     */
    static function getConstants();

    /**
     * Get the possibilities.
     *
     * @return integer[]
     */
    static function getList();
}
