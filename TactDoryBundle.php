<?php

/**************************************************************************
 * TactDoryBundle.php, TACT Dory.
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 3 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TactDoryBundle extends Bundle
{
    // public function build(ContainerBuilder $container)
    // {
    // parent::build($container);
    // // $container->addCompilerPass(new );
    // }

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::getParent()
     */
    public function getParent() {
        return 'SonataUserBundle';
    }
}
