<?php

/**************************************************************************
 * BooleanType.php, TACT Dory
 *
 * Copyright 2016
 * Description :
 * Author(s)   : TACTfactory
 * Licence     :
 * Last update : Jun 28, 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Form;

use Tact\DoryBundle\Form\DataTransformer\BooleanTypeToBooleanTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Boolean Type
 */
class BooleanType extends CheckboxType
{

    const VALUE_INT_FALSE = 0;

    const VALUE_INT_TRUE = 1;

    const VALUE_STRING_FALSE = "false";

    const VALUE_STRING_TRUE = "true";


    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new BooleanTypeToBooleanTransformer());
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getName()
    {
        return 'boolean';
    }
}
