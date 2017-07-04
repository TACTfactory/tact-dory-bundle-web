<?php

/**************************************************************************
 * AdminCollectionType.php, TACT Dory
 *
 * Copyright 2016-2017
 * Description :
 * Author(s)   : TACTfactory
 * Licence     :
 * Last update : July 04, 2017
 *
 **************************************************************************/
namespace Tact\DoryBundle\Form;

use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * AdminCollectionType.
 */
class AdminCollectionType extends CollectionType
{

    /**
     * @var string
     */
    const OPTION_GROUP_REMOVE = 'btn_grouped_remove';

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[self::OPTION_GROUP_REMOVE] = $options[self::OPTION_GROUP_REMOVE];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault(self::OPTION_GROUP_REMOVE, true);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'tactdory_type_collection';
    }
}
