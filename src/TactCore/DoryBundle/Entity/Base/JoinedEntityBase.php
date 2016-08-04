<?php

/**************************************************************************
 * JoinedEntityBase.php, Sep Conseil
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 22 avr. 2016
 *
 **************************************************************************/
namespace TactCore\DoryBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * JoinedEntityBase.
 *
 * @ORM\Entity
 * @ORM\Table(name="joined_entity_base")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JSON\ExclusionPolicy("ALL")
 */
class JoinedEntityBase implements IEntityBase
{
    use EntityBaseTrait;
}
