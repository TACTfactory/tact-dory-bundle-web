<?php

/**************************************************************************
 * AbstractControllerTest.php, TACT Dory
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s)   : Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence     : All right reserved.
 * Last update : Aug 17, 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\Tests\Base;

/**
 * AbstractControllerTest.
 */
abstract class AbstractControllerTest extends AbstractTactTest
{

    /**
     * The client to use for tests.
     *
     * @var Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client = null;

    /**
     *
     * {@inheritdoc}
     *
     */
    protected function setUp() {
        parent::setup();

        $this->client = self::createClient();

        static::rebuildDatabase();
    }
}
