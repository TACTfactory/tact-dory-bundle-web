<?php

/**************************************************************************
 * AbstractOrdererFixture.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : 5 august 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\DataFixtures\ORM\Base;

use Tact\DoryBundle\DataFixtures\ORM\Base\MainAbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Tact\DoryBundle\Entity\Base\IEntityBase;

/**
 * AbstractOrdererFixture.
 */
abstract class AbstractOrdererFixture extends MainAbstractFixture
{
    // ***** ***** *****
    // ***** ***** ***** Attributes.
    // ***** ***** *****

    /**
     * Store all data register in this fixture.
     *
     * @var FrequencyUnit[]
     */
    protected static $allObjects = [];

    // ***** ***** *****
    // ***** ***** ***** Method to override.
    // ***** ***** *****

    // Must implements getOrder from OrderedFixtureInterface.

    /**
     * Generates then returns the minimum fixtures from pure data class.
     *
     * @return IEntityBase[]
     */
    abstract protected function generateMinimumFixtures(ObjectManager $manager);

    /**
     * Generates then returns the tests fixtures.
     *
     * @return IEntityBase[]
     */
    abstract protected function generateTestFixtures(ObjectManager $manager);

    // ***** ***** *****
    // ***** ***** ***** Local overrides.
    // ***** ***** *****

    /**
     *
     * {@inheritDoc}
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $objects = $this->generateMinimumFixtures($manager);

        $kernel = $this->container->get('kernel');

        if ($kernel->getEnvironment() !== 'prod') {
            $objects = array_merge($objects, $this->generateTestFixtures($manager));
        }

        $this->saveAll($manager, $objects);
    }

    // ***** ***** *****
    // ***** ***** ***** Utils methods.
    // ***** ***** *****

    /**
     * Process persist of all given object then flush db.
     *
     * @param ObjectManager $manager
     * @param array $objects
     *
     * @return void
     */
    protected function saveAll(ObjectManager $manager, $objects)
    {
        foreach ($objects as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }

    /**
     * Get all objects created from this fixtures.
     *
     * @return EquipmentType[]
     */
    public static function getAllObjects()
    {
        return self::$allObjects;
    }

    /**
     * Create complete reference, then record object, then record complete reference.
     *
     * @param string $prefix
     * @param string $reference
     * @param IEntityBase $object
     *
     * @return string
     */
    public function recordReference($prefix, $reference, IEntityBase $object)
    {
        $completeReference = $prefix . $reference;

        $this->addReference($completeReference, $object);
        self::$allObjects[] = $completeReference;

        return $completeReference;
    }

    /**
     * Get all references from the specific prefix (or from default prefix of caller class if null).
     *
     * @param string|null $prefix
     *
     * @return IEntityBase[]
     */
    public static function getAllClassReferences($prefix)
    {
        $result = [];

        $pattern = sprintf('/%s.+/', $prefix);

        foreach (self::getAllObjects() as $reference) {
            if (preg_match($pattern, $reference)) {
                $result[] = $reference;
            }
        }

        return $result;
    }

    /**
     * Get all instances from the specific prefix (or from default prefix of caller class if null).
     *
     * @param AbstractOrdererFixture $context
     * @param string $prefix
     *
     * @return IEntityBase[]
     */
    public static function getAllClassInstances(AbstractOrdererFixture $context, $prefix)
    {
        return array_map(
                function ($reference) use($context) {
                    return $context->getReference($reference);
                }, self::getAllClassReferences($prefix));
    }

    /**
     * Get object from object or from reference.
     *
     * @param IEntityBase|string $objectOrReference
     * @param string $prefix
     *
     * @return IEntityBase
     */
    protected function getObject($objectOrReference, $prefix)
    {
        if (is_string($objectOrReference)) {
            return $this->getReference($prefix . $objectOrReference);
        } else {
            return $objectOrReference;
        }
    }
}
