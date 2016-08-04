<?php

/**************************************************************************
 * IEntityBase.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 22 avr. 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Entity\Base;

/**
 * IEntityBase.
 */
interface IEntityBase
{

    /**
     * Hook on pre-persist operations
     */
    public function prePersist();

    /**
     * Hook on pre-update operations
     */
    public function preUpdate();

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set syncUDate
     *
     * @param \DateTime $syncUDate
     *
     * @return EntityBase
     */
    public function setSyncUDate($syncUDate);

    /**
     * Get mobileId
     *
     * @return integer
     */
    public function getMobileId();

    /**
     * Set mobileId
     *
     * @param integer $mobileId
     *
     * @return EntityBase
     */
    public function setMobileId($mobileId);

    /**
     * Get syncUDate
     *
     * @return \DateTime
     */
    public function getSyncUDate();

    /**
     * Set syncDTag
     *
     * @param boolean $syncDTag
     *
     * @return EntityBase
     */
    public function setSyncDTag($syncDTag);

    /**
     * Get syncDTag
     *
     * @return boolean
     */
    public function getSyncDTag();

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return EntityBase
     */
    public function setCreateAt($createAt);

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt();

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return EntityBase
     */
    public function setUpdateAt($updateAt);

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt();

    /**
     * Set the employee state.
     *
     * Modify linked user.
     */
    public function setEnabled($newState);

    /**
     * Get if the employee is currently enable.
     *
     * @return boolean
     */
    public function isEnabled();

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled();
}
