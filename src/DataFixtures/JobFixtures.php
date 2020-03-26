<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $job = new Job();
        $job->setTitle('Developper');
        $manager->persist($job);

        $job2 = new Job();
        $job2->setTitle('President of Space');
        $manager->persist($job2);

        $job3 = new Job();
        $job3->setTitle('Pilot');
        $manager->persist($job3);

        $job4 = new Job();
        $job4->setTitle('Kung Fu Master');
        $manager->persist($job4);

        $job5 = new Job();
        $job5->setTitle('Dinosaur');
        $manager->persist($job5);

        $manager->flush();
    }
}
