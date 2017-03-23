<?php

/**************************************************************************
 * EntityBase.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2015
 * Description :
 * Author(s) : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : 5 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * EntityBase
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 *
 * @JSON\ExclusionPolicy("ALL")
 */
abstract class EntityBase implements IEntityBase
{
    use EntityBaseTrait;
}
