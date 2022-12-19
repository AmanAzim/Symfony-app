<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHaser)
    {
        
        
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword($this->userPasswordHaser->hashPassword($user1, '1234'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('test1@test.com');
        $user2->setPassword($this->userPasswordHaser->hashPassword($user2, '1234'));
        $manager->persist($user2);


        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welsome to BD');
        $microPost1->setText('Welsome to BD text');
        $microPost1->setCreatedAt(new DateTime());
        $microPost1->setAuthor($user1);

        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welsome to DE');
        $microPost2->setText('Welsome to DE text');
        $microPost2->setCreatedAt(new DateTime());
        $microPost2->setAuthor($user1);

        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welsome to PL');
        $microPost3->setText('Welsome to PL text');
        $microPost3->setCreatedAt(new DateTime());
        $microPost3->setAuthor($user2);
        
        $manager->persist($microPost3);

        $manager->flush();
    }
}
