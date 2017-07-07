<?php

namespace BackendBundle\DataFixtures\ORM\Seeds;


use BackendBundle\DataFixtures\AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class UserSeed extends AbstractDataFixture {

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  public function getOrder() {
    return 0;
  }

  /**
   * Perform the database objects
   * @param ObjectManager $manager The object manager.
   */
  protected function doLoad(ObjectManager $manager) {
    $users = [
      [
        'name' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => 'Admin123',
      ],
    ];
    foreach ($users as $user) {
      $entity = new User();
      $entity->setUsername($user['name'])
        ->setPassword($user['password'])
        ->setEnabled(true)
        ->setSuperAdmin(true)
        ->setName('')
        ->setLastname('')
        ->setPhone('')
        ->setDni('')
        ->setEmail($user['email']);

      $factory = parent::getContainer()->get('security.encoder_factory');
      $encoder = $factory->getEncoder($entity);
      $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
      $entity->setPassword($password);
      $manager->persist($entity);
      $manager->flush();
    }
  }

  /**
   * Get the environments the data fixtures are ran on
   * @return array The name of the environments.
   */
  protected function getEnvironments() {
    return ['dev'];
  }

}