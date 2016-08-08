<?php

/**************************************************************************
 * TactEnumBaseWithLabel.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2015
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Enum\Base;

/**
 * TactEnumBaseWithLabel.
 */
abstract class TactEnumBaseWithLabel extends TactEnumBase implements ITactEnumWithLabel
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function getLabelFor($constant)
    {
        $result = null;

        // Get the class that is asking for who awoke it
        $class = get_called_class();

        $possibilities = $class::getPossibilitiesWithLabel();

        if (in_array($constant, array_keys($possibilities))) {
            $result = $possibilities[$constant];
        }

        return $result;
    }
}
