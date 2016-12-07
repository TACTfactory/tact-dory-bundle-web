<?php

/**************************************************************************
 * MailAttach.php, TACT Dory
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
 * MailAttach.
 */
abstract class MailAttach implements MailAttachInterface
{

    /**
     * The attachment name.
     *
     * @var string
     */
    protected $name;

    /**
     * The attachment file name.
     *
     * @var string
     */
    protected $filename;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @return MailAttach
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the filename.
     *
     * @param string $filename
     *
     * @return MainAttach
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }
}
