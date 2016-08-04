<?php

/**************************************************************************
 * TactCoreDoryBundle.php, Tact Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 3 août 2016
 *
 **************************************************************************/
namespace TactCore\DoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TactCoreDoryBundle extends Bundle
{

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::getParent()
     */
    public function getParent() {
        return 'SonataUserBundle';
    }
}
