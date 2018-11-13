<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CAT_REFERENCE_0 = 'cat0';
    public const CAT_REFERENCE_1 = 'cat1';
    public const CAT_REFERENCE_2 = 'cat2';
    public const CAT_REFERENCE_3 = 'cat3';
    public const CAT_REFERENCE_4 = 'cat4';


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName('category '.$i);
            $manager->persist($category);

            switch ($i) {
                case 0 :
                    $this->addReference(self::CAT_REFERENCE_0, $category);
                    break;
                case 1 :
                    $this->addReference(self::CAT_REFERENCE_1, $category);
                    break;
                case 2 :
                    $this->addReference(self::CAT_REFERENCE_2, $category);
                    break;
                case 3 :
                    $this->addReference(self::CAT_REFERENCE_3, $category);
                    break;
                case 4 :
                    $this->addReference(self::CAT_REFERENCE_4, $category);
                    break;
            }
        }

        $manager->flush();
    }
}