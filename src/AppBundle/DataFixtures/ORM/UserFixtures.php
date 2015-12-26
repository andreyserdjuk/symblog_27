<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\FOSUserChild;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $passwordEncoder = $this->container->get('security.password_encoder');

        $user = $userManager->createUser();
        $user->setEmail('fixture@superadmin.com');
        $user->setEnabled(true);
        $user->setPassword(
            $passwordEncoder->encodePassword($user, '12345')
        );
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setUsername('fix');
        $manager->persist($user);
        $manager->flush();

        $userManager->updateUser($user, true);
    }
}