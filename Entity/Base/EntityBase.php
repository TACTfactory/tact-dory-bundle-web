<?php

/**************************************************************************
 * EntityBase.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2015
 * Description :
 * Author(s) : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : Jun 02, 2015
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
