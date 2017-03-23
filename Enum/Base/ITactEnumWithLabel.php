<?php

/**************************************************************************
 * ITactEnumWithLabel.php, TACT Dory
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
 * ITactEnumWithLabel.
 */
interface ITactEnumWithLabel extends ITactEnum
{

    /**
     * Get the possibilities with labels.
     *
     * This method is make to be call to fill HTML select tags (radio or other).
     *
     * @return string[] All necessary constants (as keys) with a french label (as value).
     */
    static function getListWithLabel();

    /**
     * Get the label for the given constant.
     *
     * @param mixed $constant
     *            One constant of the child class.
     *
     * @return string
     */
    static function getLabelFor($constant);
}
