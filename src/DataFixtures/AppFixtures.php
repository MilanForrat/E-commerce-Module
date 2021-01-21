<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++){
            $category = new Category();
            $category->setName($faker->name);
        }

        for($i = 0; $i < 100; $i++){
            $product = new Product();
            $product->setName($faker->name);
            $product->setPrice($faker->numberBetween($min = 15, $max = 999));
            $product->setDescription($faker->sentence($nbWords = 10, $variableNbWords = true));
            $product->setContent($faker->text($maxNbChars = 200));
            $product->setImage($faker->imageUrl($width = 290, $height = 180));
            $manager->persist($product); // on persist en boucle
        }

        $manager->flush();
    }
}
