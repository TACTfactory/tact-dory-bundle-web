<?php

/**************************************************************************
 * MailAttachInterface.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 7 déc. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Models\Base;

/**
 * MailAttachModel.
 */
interface MailAttachInterface
{

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the filename.
     *
     * @return string
     */
    public function getFilename();
}
