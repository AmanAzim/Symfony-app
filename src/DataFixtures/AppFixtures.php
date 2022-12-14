<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welsome to BD');
        $microPost1->setText('Welsome to BD text');
        $microPost1->setCreatedAt(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welsome to DE');
        $microPost2->setText('Welsome to DE text');
        $microPost2->setCreatedAt(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welsome to PL');
        $microPost3->setText('Welsome to PL text');
        $microPost3->setCreatedAt(new DateTime());

 
        
        $manager->persist($microPost3);

        $manager->flush();
    }
}
