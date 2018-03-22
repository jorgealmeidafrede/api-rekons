<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 20/03/18
 * Time: 14:15
 */

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct (UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load (ObjectManager $manager)
    {

// user admin
       $user = new User();
       $user->setName('admin');
       $user->setAddress('Dos de Maig 257');
       $user->setRoles(['ROLE_ADMIN']);
       $user->setEmail('admin@admin.com');
       $user->setPassword($this->encoder->encodePassword($user , 'admin123'));
// user user
        $userUser = new User();
        $userUser->setName('user');
        $userUser->setAddress('Urgell 32');
        $userUser->setRoles(['ROLE_USER']);
        $userUser->setEmail('user@user.com');
        $userUser->setPassword($this->encoder->encodePassword($user , 'user123'));


       $manager->persist($user);
       $manager->persist($userUser);
        $manager->flush();
    }
}