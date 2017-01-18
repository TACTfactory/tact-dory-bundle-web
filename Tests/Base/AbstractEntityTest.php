<?php

/**************************************************************************
 * AbstractEntityTest.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2015
 * Description :
 * Author(s) : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : Aug 17, 2015
 *
 **************************************************************************/
namespace Tact\DoryBundle\Tests\Base;

abstract class AbstractEntityTest extends AbstractTactTest
{
    /**
     * Generate then return an entity that
     */
    abstract protected function generateEntity();

    // Configurations methods.

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function setUp() {
        parent::setup();

        $this->entity = $this->generateEntity();
        $this->repository = $this->em->getRepository(get_class($this->entity));

        static::rebuildDatabase();
    }

    // Tests.

    /**
     * Test to update the entity.
     */
    public abstract function testUpdate();

    /**
     * Test to create then add one entity to database.
     */
    public function testCreate() {
        $this->em->persist($this->entity);
        $this->em->flush();
        $this->assertNotEmpty($this->entity->getId());
    }

    /**
     * Test to read all entities from database.
     */
    public function testReadAll() {
        $this->entity = $this->repository->findAll();
        $this->assertNotEmpty($this->entity, 'Empty database (no fixture for this entity) or problem(s) on database.');
    }

    /**
     * Test to select one entity from database.
     */
    public function testReadOne() {
        $this->entity = $this->repository->findOneById($this->id);
        $this->assertNotEmpty($this->entity, sprintf(
                'Empty database or database problem or wrong setted id (default %d).', self::DEFAULT_ID));
    }

    /**
     * Test to delete one entity from database.
     */
    public function testDelete() {
        $this->em->persist($this->entity);
        $this->em->flush();
        $id = $this->entity->getId();
        $entity = $this->repository->findOneById($id);
        $this->em->remove($entity);
        $this->em->flush();
        $this->assertNull($this->repository->findOneById($id));
    }

    // Utils methods.

    /**
     * Does some tests to check that accessors are good.
     *
     * @param string $fieldName
     *            The name of the field to test.
     * @param unknown $newValue
     *            The new value to set for test (assert if same than current).
     */
    protected function assertInvalidAccessor(string $fieldName, $newValue) {
        $setter = sprintf('set%s', ucfirst($fieldName));
        $getter = sprintf('get%s', ucfirst($fieldName));

        if (method_exists($this->entity, $getter) == false) {
            $getter = sprintf('is%s', ucfirst($fieldName));

            if (method_exists($this->entity, $getter) == false) {
                $getter = sprintf('has%s', ucfirst($fieldName));

                if (method_exists($this->entity, $getter) == false) {
                    $this->assert(sprintf(
                            'No found method to get "%s" field (neither get nor is nor has).', $fieldName));
                }
            }
        }

        $initialValue = $this->entity->$getter();

        $this->entity->$setter($newValue);

        $this->assertTrue($initialValue !== $this->entity->$getter());
        $this->assertEquals($newValue, $this->entity->$getter());
    }
}
