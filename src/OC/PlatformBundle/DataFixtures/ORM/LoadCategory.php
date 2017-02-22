<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\category;

class LoadCategory implements FixtureInterface
{
    // Dans l'argument de la m�thode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de cat�gorie � ajouter
        $names = array(
            'Developpement web',
            'Developpement mobile',
            'Graphisme',
            'Integration',
            'Reseau'
        );

        foreach ($names as $name) {
            // On cr�e la cat�gorie
            $category = new category();
            $category->setName($name);

            // On la persiste
            $manager->persist($category);
        }

        // On d�clenche l'enregistrement de toutes les cat�gories
        $manager->flush();
    }
}