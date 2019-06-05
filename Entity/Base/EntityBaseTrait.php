<?php

/**************************************************************************
 * EntityBaseTrait.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 august 2016
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
     * @ORM\Column(name="created_at", type="datetime")
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("createdAt")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable = true)
     * @JSON\Expose
     * @JSON\Groups({"api_process"})
     * @JSON\Since("1.0")
     * @JSON\SerializedName("updatedAt")
     *
     * @var \DateTime
     */
    private $updatedAt;

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
        $now = new \DateTime();

        $this->createdAt  = $this->prePersistOneDate($this->createdAt,  $now);
        $this->updatedAt  = $this->prePersistOneDate($this->updatedAt,  $now);
        $this->sync_uDate = $this->prePersistOneDate($this->sync_uDate, $now);

        $this->sync_dTag  = $this->sync_dTag ?? false;
    }

    /**
     * Conserve the known date if exists or create new one with the current date.
     *
     * @param \DateTime $field
     *            The field to update.
     * @param \DateTime $date
     *            The default value for date field.
     *
     * @return \DateTime
     */
    private function prePersistOneDate($field, $date)
    {
        if ($field === null) {
            $field = $date;
        }

        return $field;
    }

    /**
     * Hook on pre-update operations
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set id
     * @param integer $id
     *
     * @return EntityBase
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EntityBase
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return EntityBase
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
