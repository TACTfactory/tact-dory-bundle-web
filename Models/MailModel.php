<?php

/**************************************************************************
 * MailModel.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 7 déc. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Models;

use FOS\UserBundle\Model\UserInterface;

/**
 * MailModel.
 */
class MailModel
{

    /**
     * The mail address that send the email.
     *
     * @var string
     */
    protected $transmitter;

    /**
     * The user (the email target).
     *
     * @var FOS\UserBundle\Model\UserInterface
     */
    protected $recipient;

    /**
     * The email subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * The twig which describe email content.
     *
     * @var string
     */
    protected $twig;

    /**
     * The required parameters to generate the twig.
     *
     * @var array
     */
    protected $twigParameters;

    /**
     * Constructor.
     *
     * @param string $transmitter
     *            The email sender.
     * @param UserInterface $recipient
     *            The email receiver.
     * @param string $subject
     *            The email subject.
     * @param string $twig
     *            The content twig.
     * @param array $twigParameters
     *            The twig parameters.
     */
    public function __construct(string $transmitter = null, UserInterface $recipient = null, string $subject = null,
            string $twig = null, array $twigParameters = [])
    {
        $this->transmitter = $transmitter;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->twig = $twig;
        $this->twigParameters = $twigParameters;
    }

    /**
     * Get the transmitter.
     *
     * @return string
     */
    public function getTransmitter(): string
    {
        return $this->transmitter;
    }

    /**
     * Set the transmitter.
     *
     * @param string $transmitter
     *
     * @return MailModel
     */
    public function setTransmitter(string $transmitter)
    {
        $this->transmitter = $transmitter;

        return $this;
    }

    /**
     * Get the recipient.
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set the recipient.
     *
     * @param \FOS\UserBundle\Model\UserInterface $recipient
     *
     * @return MailModel
     */
    public function setRecipient(\FOS\UserBundle\Model\UserInterface $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get the subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the subject.
     *
     * @param string $subject
     *
     * @return MailModel
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the twig.
     *
     * @return string
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Set the twig.
     *
     * @param string $twig
     *
     * @return MailModel
     */
    public function setTwig(string $twig)
    {
        $this->twig = $twig;

        return $this;
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
     * @return MailModel
     */
    public function setTwigParameters(array $twigParameters)
    {
        $this->twigParameters = $twigParameters;

        return $this;
    }
}
