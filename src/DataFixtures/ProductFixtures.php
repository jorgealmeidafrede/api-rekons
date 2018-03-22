<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 20/03/18
 * Time: 15:26
 */

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private $nameOfProduct = [
        '0350' => 'Empanada de Carne Picante',
        '0351' => 'Empanada de Carne',
        '0352' => 'Empanada de Pollo',
        '0353' => 'Empanada de Atun',
        '0354' => 'Empanada de Espárragos Trigueros',
        '0355' => 'Empanada de Espinacas',
        '0356' => 'Empanada de Champinion con Queso',
        '0357' => 'Empanada de Cebolla y Queso',
        '0358' => 'Empanada de Berenjena',
        '0359' => 'Empanada de Calabacín',
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load (ObjectManager $manager)
    {
//        for ($i = 0 ; $i < count($this->$nameOfProduct); $i++){
//            $product = new Product();
//            $product->setName();
//        }
        foreach ($this->nameOfProduct as $code => $name){
            $product = new Product();
            $product->setCode($code);
            $product->setName($name);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt ');
            $manager->persist($product);
        }

        $manager->flush();
    }
}

