<?php

/**************************************************************************
 * EntityBaseTrait.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 août 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * EntityBaseTrait.
 */
trait EntityBaseTrait
{

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     *
     * @var integer
     */
    private $id;

    /**
     * @JSON\Type("integer")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("mobileId")
     *
     * @var integer
     */
    private $mobileId;

    /**
     * Sync update date.
     *
     * @ORM\Column(name="sync_udate", type="datetime")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("sync_uDate")
     *
     * @var \DateTime
     */
    private $sync_uDate;

    /**
     * Sync delete tag.
     *
     * @ORM\Column(name="sync_dtag", type="boolean")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("sync_dTag")
     *
     * @var boolean
     */
    private $sync_dTag;

    /**
     * @ORM\Column(name="create_at", type="datetime")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("createAt")
     *
     * @var \DateTime
     */
    private $createAt;

    /**
     * @ORM\Column(name="update_at", type="datetime", nullable = true)
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("updateAt")
     *
     * @var \DateTime
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean", options = {"default" = true})
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     *
     * @var boolean
     */
    private $enabled = true;

    /**
     * Display string of object
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * Default constructor.
     */
    public function __construct()
    {}

    /**
     * This method clone recursivly the direct object (datetime, etc) but not the doctrine entities.
     *
     * {@inheritdoc}
     *
     */
    public function __clone()
    {
        $object_vars = get_object_vars($this);

        // Do not refer to sub-object, clone them :
        foreach ($object_vars as $name => $value) {
            if (is_object($value)) {
                $this->$name = clone ($this->$name);
            }
        }

        $this->id = null;
    }

    /**
     * Hook on pre-persist operations
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createAt = $this->prePersistOneDate($this->createAt);
        $this->updateAt = $this->prePersistOneDate($this->updateAt);
        $this->sync_uDate = $this->prePersistOneDate($this->sync_uDate);

        $this->sync_dTag = false;
    }

    /**
     * Conserve the known date if exists or create new one with the current date.
     *
     * @param \DateTime $date
     *            The date to update.
     *
     * @return \DateTime
     */
    private function prePersistOneDate($date)
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        return $date;
    }

    /**
     * Hook on pre-update operations
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updateAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set syncUDate
     *
     * @param \DateTime $syncUDate
     *
     * @return EntityBase
     */
    public function setSyncUDate($syncUDate)
    {
        $this->sync_uDate = $syncUDate;

        return $this;
    }

    /**
     * Get mobileId
     *
     * @return integer
     */
    public function getMobileId()
    {
        return $this->mobileId;
    }

    /**
     * Set mobileId
     *
     * @param integer $mobileId
     *
     * @return EntityBase
     */
    public function setMobileId($mobileId)
    {
        $this->mobileId = $mobileId;

        return $this;
    }

    /**
     * Get syncUDate
     *
     * @return \DateTime
     */
    public function getSyncUDate()
    {
        return $this->sync_uDate;
    }

    /**
     * Set syncDTag
     *
     * @param boolean $syncDTag
     *
     * @return EntityBase
     */
    public function setSyncDTag($syncDTag)
    {
        $this->sync_dTag = $syncDTag;

        return $this;
    }

    /**
     * Get syncDTag
     *
     * @return boolean
     */
    public function getSyncDTag()
    {
        return $this->sync_dTag;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return EntityBase
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return EntityBase
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set the employee state.
     *
     * Modify linked user.
     */
    public function setEnabled($newState)
    {
        $this->enabled = $newState;
    }

    /**
     * Get if the employee is currently enable.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    // ***** ***** *****
    // ***** ***** ***** Utils methods.
    // ***** ***** *****

    /**
     * Try to find the logical name for the linked repository.
     *
     * @throws \Exception
     */
    public static function getRepositoryClass()
    {
        $result = '';
        $matches = [];
        $className = get_called_class();

        if (preg_match('/^(\w+)\\\\(\w+)\\\\Entity\\\\(\w+)$/', $className, $matches)) {
            $result = sprintf('%s%s:%s', $matches[1], $matches[2], $matches[3]);
        } else {
            throw new \Exception('Impossible to automatically determine repository class for %s', $className);
        }

        return $result;
    }
}
