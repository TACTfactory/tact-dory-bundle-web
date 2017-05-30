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

use Symfony\Bundle\FrameworkBundle\Client;

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
     * {@inheritdoc}
     *
     * Only defined here for retro-compatibilities, thanks to return a real entity if you want have automatic stuffs.
     */
    protected function generateEntity() {
        return null;
    }

    /**
     * Generates then returns a logged user.
     *
     * @param string $user
     * @param string $password
     * @param array $options
     * @param array $server
     *
     * @return Client
     */
    protected function generateClient(string $user, string $password, array $options = [], array $server = []): Client
    {
        return static::createClient($options,
                array_merge(
                        [
                            'PHP_AUTH_USER' => $user,
                            'PHP_AUTH_PW' => $password
                        ], $server));
    }

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
