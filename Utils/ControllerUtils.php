<?php

/**************************************************************************
 * ControllerUtils.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 3 oct. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * ControllerUtils.
 */
abstract class ControllerUtils
{

    /**
     * Mime about application.
     *
     * @var string
     */
    const MIME_APPLICATION = 'application';

    /**
     * Mime about text.
     *
     * @var string
     */
    const MIME_TEXT        = 'text';

    /**
     * Handle the given form.
     *
     * @param Request $request
     * @param Form $form
     * @param bool $html
     *
     * @return \Symfony\Component\Form\Form
     */
    public function handleForm(Request $request, Form $form, bool $html = null)
    {
        if ($html == null) {
            $html = self::askHTML($request);
        }

        if ($html) {
            $form->handleRequest($request);
        } else {
            $form->submit($request->request->all());
        }

        return $form;
    }

    /**
     * Tests if the request ask html response.
     *
     * @param Request $request
     *
     * @return boolean
     */
    public static function askHTML(Request $request) {
        return $request->isXmlHttpRequest() == false && self::askGivenFormat($request, 'html', self::MIME_TEXT);
    }

    /**
     * Check if the format take from request is json.
     *
     * @param Request $request
     *
     * @return boolean True if it is, false otherwise.
     */
    protected static function askGivenFormat(Request $request, string $format, string $mime)
    {
        $complete = sprintf('%s/%s', $mime, $format);

        return $request->query->get('_format')       == $format                // GET parameter.
                || $request->request->get('_format') == $format                // Post parameter.
                || in_array($complete, $request->getAcceptableContentTypes()); // Records as complients.
    }
}
