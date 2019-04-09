<?php

/**************************************************************************
 * UserAdmin.php, TACT Dory
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

class UserAdmin extends \Sonata\UserBundle\Admin\Model\UserAdmin
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Sonata\UserBundle\Admin\Model\UserAdmin::configureFormFields()
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        parent::configureFormFields($formMapper);

        $now = new \DateTime();

        $formMapper->tab('User')
            ->with('Profile')
            ->add('dateOfBirth', 'sonata_type_date_picker',
                array(
                    'years' => range(1900, $now->format('Y')),
                    'required' => false,
                    'translation_domain' => $this->getTranslationDomain(),
                    'format' => 'dd/MM/y'
                ))
            ->end()
            ->end();
    }
}
