<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture {
    /**
     * @var Generator
     */
    private Generator $faker;
    public function __construct() {
        $this->faker = Factory::create('fr_FR');
    }
        
    public function load(ObjectManager $manager): void {
        for ($i = 1; $i < 20; $i++) { 
            $ingredient = new Ingredients();
            $ingredient->setName($this->faker->word())->setPrice(mt_rand(0, 15));
            $manager->persist($ingredient);   
        }
        $manager->flush();
    }
}
