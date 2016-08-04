<?php

/**************************************************************************
 * TactEnumBase.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2015
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 9 déc. 2015
 *
 **************************************************************************/
namespace Tact\DoryBundle\Enum\Base;

/**
 * TactEnumBase.
 */
abstract class TactEnumBase implements ITactEnum
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function getConstants()
    {
        $oClass = new \ReflectionClass(get_called_class());

        return $oClass->getConstants();
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function getPossibilities()
    {
        return array_unique(array_values(self::getConstants()));
    }
}
