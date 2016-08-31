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

        self::bootKernel();

        // Init Entity Manager
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->id = 1;

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
        $this->assertNotEmpty($this->entity);
    }

    /**
     * Test to select one entity from database.
     */
    public function testReadOne() {
        $this->entity = $this->repository->findOneById($this->id);
        $this->assertNotEmpty($this->entity);
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
}
