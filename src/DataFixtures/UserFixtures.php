<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $hasher)
  {
  }

  public function load(ObjectManager $manager): void
  {
    $user = new User();
    $user->setName('Test User');
    $user->setEmail('test_user@test.com');
    $user->setPassword($this->hasher->hashPassword($user, 'test_password'));
    $user->setRoles(['ROLE_USER']);
    $manager->persist($user);

    $manager->flush();
  }
}
