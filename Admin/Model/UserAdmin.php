<?php

/**************************************************************************
 * UserAdmin.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 18 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Admin\Model;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class UserAdmin extends \Sonata\UserBundle\Admin\Model\UserAdmin
{

    protected function configureFormFields(FormMapper $formMapper) {
        parent::configureFormFields($formMapper);

        $formMapper->add('toto', 'text', [
            'mapped' => false
        ]);
    }
}
