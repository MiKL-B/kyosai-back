<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            # code...

            $produit = new Produits();
            $produit->setNom($faker->name)
                ->setImage($faker->imageUrl(640,  480))
                ->setPrix($faker->numberBetween(5,  20));

                $manager->persist($produit);
        }
        $manager->flush();
    }
}
