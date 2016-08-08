<?php

/**************************************************************************
 * UserFixtureHelper.php, CmDB
 *
 * Mickael Gaillard Copyright 2016
 * Description :
 * Author(s) : Mickael Gaillard <mickael.gaillard@tactfactory.com>
 *             Jonathan Poncy <jonathan.poncy@tactfactory.com>
 * Licence : All right reserved.
 * Last update : Jan 25, 2016
 *
 **************************************************************************/
namespace Tact\DoryBundle\DataFixtures\ORM;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\Common\Persistence\ObjectManager;
use Tact\DoryBundle\Entity\User;
use Tact\DoryBundle\DataFixtures\ORM\Base\AbstractOrdererFixture;
use FOS\UserBundle\Doctrine\UserManager;

abstract class UserFixtureHelper extends AbstractOrdererFixture
{

    /**
     * User manager service.
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The test fixture object count.
     *
     * @var integer
     */
    protected $count = 0;

    /**
     * Make then return a user fixture object.
     *
     * @param string $username
     * @param boolean $enable
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string[] $roles
     * @param string $email
     * @param \DateTime $birthday
     * @param string $website
     * @param char $gender
     * @param string $locale
     * @param string $timeZone
     * @param string $phone
     * @param string $ref
     *
     * @return \Tact\DoryBundle\Entity\User
     */
    abstract protected function makeUser($username, $enable, $password, $firstname, $lastname, $roles, $email, $birthday,
            $website, $gender, $locale, $timeZone, $phone, $ref = null);

    /**
     *
     * {@inheritdoc}
     *
     * @see \Tact\DoryBundle\DataFixtures\ORM\Base\AbstractOrdererFixture::generateMinimumFixtures()
     */
    protected function generateMinimumFixtures(ObjectManager $manager) {
        $result = [];

        $this->userManager = $this->container->get('fos_user.user_manager');

        // Administrator
        $result[] = $this->makeUser('admin', true, 'TJ8K257Z9A1lEVA', 'Administrator', '',
                array(
                    User::ROLE_DEFAULT,
                    User::ROLE_API,
                    User::ROLE_SUPER_ADMIN
                ), "admin@alfred.com", new \DateTime('2014-01-01'), 'http://www.tactfactory.com', User::GENDER_UNKNOWN,
                'fr_FR', 'Europe/Paris', '+33100000000', 'user_admin');

        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Tact\DoryBundle\DataFixtures\ORM\Base\AbstractOrdererFixture::generateTestFixtures()
     */
    protected function generateTestFixtures(ObjectManager $manager) {
        $result = [];

        for ($i = 4; $i <= 150; $i ++) {
            $result[] = $this->makeUser($this->faker->unique()->firstNameMale, true, '0', $this->faker->firstNameMale,
                    $this->faker->lastName,
                    array(
                        User::ROLE_DEFAULT,
                        User::ROLE_API
                    ), $this->faker->email, $this->faker->dateTimeThisCentury, 'http://www.test.com', User::GENDER_MALE,
                    $this->faker->locale, $this->faker->timezone, $this->faker->phoneNumber, $i);
        }

        return $result;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder() {
        return 100;
    }

    /**
     * Make a user fixture object.
     *
     * @param BaseUser $user
     *            The user entity to fill.
     * @param string $username
     * @param boolean $enable
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string[] $roles
     * @param string $email
     * @param \DateTime $birthday
     * @param string $website
     * @param char $gender
     * @param string $locale
     * @param string $timeZone
     * @param string $phone
     * @param string $ref
     *
     * @return \Tact\DoryBundle\Entity\User
     */
    protected function fillUser(BaseUser $user, $username, $enable, $password, $firstname, $lastname, $roles, $email,
            $birthday, $website, $gender, $locale, $timeZone, $phone) {
        ++ $this->count;

        $user->setUsername($username);
        $user->setEnabled($enable);
        $user->setPlainPassword($password);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setRoles($roles);
        $user->setEmail($email);
        $user->setDateOfBirth($birthday);
        $user->setWebsite($website);
        $user->setGender($gender);
        $user->setLocale($locale);
        $user->setTimezone($timeZone);
        $user->setPhone($phone);
        $user->setProfilePicturePath('img.jpg');

        return $user;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Tact\DoryBundle\DataFixtures\ORM\Base\AbstractOrdererFixture::generateTestFixtures()
     */
    protected function saveAll(ObjectManager $manager, $objects) {
        foreach ($objects as $object) {
            $this->userManager->updateUser($object, true);
        }

        $manager->flush();
    }
}
