<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;
        $product->setName("Product One");
        $product->setDescription("The Description Text");
        $product->setSize(1337);
        $manager->persist($product);

        $product = new Product;
        $product->setName("Product Two");
        $product->setDescription("Another Description Text");
        $product->setSize(2352354);
        $manager->persist($product);

        $manager->flush();
    }
}
