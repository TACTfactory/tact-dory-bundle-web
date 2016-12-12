<?php

/**************************************************************************
 * MailAttachTwig.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 7 déc. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Models;

use Tact\DoryBundle\Models\Base\MailAttachInterface;

/**
 * MailAttachTwig.
 */
class MailAttachTwig implements MailAttachInterface
{

    /**
     * The required parameters to generate the twig.
     *
     * @var array
     */
    protected $twigParameters;

    /**
     * Constructor.
     *
     * @param string $twig
     * @param array $twigParameters
     */
    public function __construct(string $twig, array $twigParameters = [])
    {
        $this->setFilename($twig);
        $this->twigParameters = $twigParameters;
    }

    /**
     * Get the twigParameters.
     *
     * @return array
     */
    public function getTwigParameters()
    {
        return $this->twigParameters;
    }

    /**
     * Set the twigParameters.
     *
     * @param array $twigParameters
     *
     * @return MailAttachTwig
     */
    public function setTwigParameters(array $twigParameters)
    {
        $this->twigParameters = $twigParameters;

        return $this;
    }
}
