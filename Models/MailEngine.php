<?php

/**************************************************************************
 * MailEngine.php, TACT Dory
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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MailEngine.
 */
class MailEngine implements ContainerAwareInterface
{

    const PDF_PATH = '../../../../web/uploads/pdf/';

    /**
     * Key of twig parameters to get Dory::MailEngine parameters.
     *
     * @var string
     */
    const PARAMETER_KEY    = 'mailer';

    /**
     * Error message if given twig parameter array contains the PARAMETER_KEY as root key.
     *
     * @var string
     */
    const ERROR_MAILER_KEY = 'You put a twig parameter with "' . self::PARAMETER_KEY .
            '" as key, this key is reserved by our method.';

    /**
     * Error if the given template is not existing.
     *
     * @var string
     */
    const ERROR_FLAG_INVALID_TEMPLATE_NAME = 'Asked from %s, invalid "%s" as template name.';

    /**
     * The MIME used for mails.
     *
     * @var string
     */
    const MAIL_MIME = 'text/html';

    /**
     * Injected mailer service.
     */
    private $mailer;

    /**
     * The uses template service.
     *
     * @var EngineInterface
     */
    private $templating;

    /**
     * The uses translator service.
     *
     * @var TranslatorInterface
     */
    private $translator;

    private $pdfGenerator = null;

    /**
     * Constructor.
     */
    public function __construct()
    {}

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\DependencyInjection\ContainerAwareInterface::setContainer()
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container !== null) {
            $this->mailer     = $container->get('mailer');
            $this->templating = $container->get('templating');
            $this->translator = $container->get('translator');

            if ($container->has('dory.model.mailer')) {
                $this->pdfGenerator = $container->get('dory.model.mailer');
            }
        }
    }

    /**
     * Send email.
     *
     * @param string $from
     * @param UserInterface $user
     * @param string $subject
     * @param string $twig
     * @param array $params
     *              The params for the twig render (mailer if forbidden as key).
     * @param array $bccs
     * @param array|\Tact\DoryBundle\Models\Base\MailAttachInterface[] $twigAttachs
     * @param array|string[] $recipientEmails The "to" mail addresses (use key to set your name if you want this).
     *
     * @return int The number of successful recipients. Can be 0 which indicates failure.
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function sendMessage(string $from, UserInterface $user, string $subject, string $twig, $params = array(),
            array $bccs = [], array $twigAttachs = [], array $recipientEmails = null)
    {
        if ($this->mailer === null) {
            throw new \Exception('Failure of dory.model.mailer service initialization (don\'t have dependencies).');
        }

        $locale = $this->translator->getLocale();

        if (method_exists($user, 'getLocale') && $user->getLocale() !== null) {
            $locale = $user->getLocale();
            $this->translator->setLocale($locale);
        }

        $message = \Swift_Message::newInstance();

        $message->setFrom($from);
        $message->setContentType("text/html");

        if ($recipientEmails === null || count($recipientEmails) === 0) {
            $message->setTo($user->getEmail());
        } else {
            foreach ($recipientEmails as $name => $address) {
                if (is_string($name)) {
                    $message->addTo($address, $name);
                } else {
                    $message->addTo($address);
                }
            }
        }

        // Add bbcs.
        $message->setBcc($bccs);

        // Process attachements.
        foreach ($twigAttachs as $attach) {
            // TODO Upgrade attachements.
            $attachmentName = '';
            $attachmentFilename = '';

            if ($attach instanceof \Tact\DoryBundle\Model\MailAttachTwig) {
                if ($this->pdfGenerator === null) {
                    throw new \Exception('Your cannot add twig attachment without pdf genetor (knp\snappy).');
                }

                // It's a twig, we need to convert him to pdf.
                $this->templating->render($attach->getTwig(), $attach->getTwigParameters());

                // Generate random name for attach file.
                $attachmentFilename = sprintf('%s%s.pdf', static::PDF_PATH, sha1(uniqid(mt_rand(), true)));

                // Convert Attach file from html to pdf.
                $snappy = $this->context->get('knp_snappy.pdf');

                $snappy->generateFromHtml($htmlAttach, $attachmentFilename, array(), false);
            } else {
                // It's a direct object (PDF for example).
                $attachmentName = $attach->getName();
                $attachmentFilename = $attach->getFilename();
            }

            $message->attach(\Swift_Attachment::fromPath($attachmentFilename)->setFilename($attachmentName));
        }

        if ($twig !== null && $this->templating->exists($twig)) {
            $translatedSubject = $this->translator->trans($subject);

            // Add customs parameters to render view.
            $params = $this->addOurMailTwigParameters($params, $locale);

            // Render Mail body content (html).
            $htmlBody = $this->templating->render($twig, $params);

            $message->setSubject($translatedSubject)->setBody($htmlBody, static::MAIL_MIME);
        } else {
            throw new \InvalidArgumentException(sprintf(self::ERROR_FLAG_INVALID_TEMPLATE_NAME, __CLASS__, $twig));
        }

        return $this->mailer->send($message);
    }

    /**
     * Send a message from a Mail model.
     *
     * @param MailModel $mailModel
     *
     * @return int The number of successful recipients. Can be 0 which indicates failure.
     */
    public function sendMessageFromModel(MailModel $mailModel)
    {
        if ($mailModel->isValid() === false) {
            throw new InvalidMailModelException();
        }

        return $this->sendMessage($mailModel->getTransmitter(), $mailModel->getRecipient(), $mailModel->getSubject(),
                $mailModel->getTwig(), $mailModel->getTwigParameters(), $mailModel->getRecipientEmails());
    }

    /**
     * Add our parameters into twig parameters.
     *
     * Only locale for now.
     *
     * @param array $twigParameters
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function addOurMailTwigParameters(array $twigParameters, string $locale)
    {
        if (isset($twigParameters[static::PARAMETER_KEY])) {
            throw new \InvalidArgumentException(static::ERROR_MAILER_KEY);
        }

        $twigParameters[static::PARAMETER_KEY] = [
            'locale' => $locale
        ];

        return $twigParameters;
    }
}
