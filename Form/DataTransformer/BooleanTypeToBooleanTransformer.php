<?php

/**************************************************************************
 * BooleanTypeToBooleanTransformer.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : TACTfactory
 * Licence     :
 * Last update : Jun 28, 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Form\DataTransformer;

use Tact\DoryBundle\Form\BooleanType;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Boolean Type Transformer
 */
class BooleanTypeToBooleanTransformer implements DataTransformerInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function transform($value)
    {
        $result = BooleanType::VALUE_INT_FALSE;

        if (true === $value || BooleanType::VALUE_INT_TRUE === (int) $value || BooleanType::VALUE_STRING_TRUE === $value) {
            $result = BooleanType::VALUE_INT_TRUE;
        }

        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function reverseTransform($value)
    {
        $result = false;

        if (BooleanType::VALUE_INT_TRUE === (int) $value || true === $value || BooleanType::VALUE_STRING_TRUE === $value) {
            $result = true;
        }

        return $result;
    }
}
