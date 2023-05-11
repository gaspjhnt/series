<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries($manager);
    }

    public function addSeries(ObjectManager $manager){

        $generator = Factory::create('fr_FR');


        for ($i = 0; $i < 50; $i++){

            $serie = new Serie();
            $serie
                ->setBackdrop($generator->word.".png")
                ->setDateCreated($generator->dateTimeBetween("- 20 years"))
                ->setGenres($generator->randomElement(["Western", "SF",  "Drama", "Comedy"]))
                ->setName($generator->word.$i)
                ->setFirstAirDate($generator->dateTimeBetween("- 10 years", "- 1 year"))
                ->setLastAirDate(new \DateTime("-2 month"))
                ->setPopularity($generator->numberBetween(0,1000))
                ->setPoster($generator->word.".png")
                ->setStatus($generator->randomElement(["Canceled", "In Progress"]))
                ->setTmdbId($generator->numberBetween(0,9999))
                ->setVote($generator->numberBetween(0,10));

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
