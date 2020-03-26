<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Repository\JobRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            JobFixtures::class
        );
    }

    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0;$i<20;$i++){
            $employee = new Employee();
            $employee->setFirstname($faker->firstName);
            $employee->setLastname($faker->lastName);
            $employee->setEmployementDate($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null));
            $employee->setJob($this->jobRepository->find(rand(1,5)));
            $manager->persist($employee);
        }
        $manager->flush();
    }
}
