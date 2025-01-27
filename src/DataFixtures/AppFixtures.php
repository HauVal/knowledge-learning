<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Cursus;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste des données
        $themesData = [
            'Musique' => [
                [
                    'name' => 'Cursus d’initiation à la guitare',
                    'price' => 50,
                    'lessons' => [
                        ['name' => 'Découverte de l’instrument', 'price' => 26],
                        ['name' => 'Les accords et les gammes', 'price' => 26],
                    ],
                ],
                [
                    'name' => 'Cursus d’initiation au piano',
                    'price' => 50,
                    'lessons' => [
                        ['name' => 'Découverte de l’instrument', 'price' => 26],
                        ['name' => 'Les accords et les gammes', 'price' => 26],
                    ],
                ],
            ],
            'Informatique' => [
                [
                    'name' => 'Cursus d’initiation au développement web',
                    'price' => 60,
                    'lessons' => [
                        ['name' => 'Les langages Html et CSS', 'price' => 32],
                        ['name' => 'Dynamiser votre site avec Javascript', 'price' => 32],
                    ],
                ],
            ],
            'Jardinage' => [
                [
                    'name' => 'Cursus d’initiation au jardinage',
                    'price' => 30,
                    'lessons' => [
                        ['name' => 'Les outils du jardinier', 'price' => 16],
                        ['name' => 'Jardiner avec la lune', 'price' => 16],
                    ],
                ],
            ],
            'Cuisine' => [
                [
                    'name' => 'Cursus d’initiation à la cuisine',
                    'price' => 44,
                    'lessons' => [
                        ['name' => 'Les modes de cuisson', 'price' => 23],
                        ['name' => 'Les saveurs', 'price' => 23],
                    ],
                ],
                [
                    'name' => 'Cursus d’initiation à l’art du dressage culinaire',
                    'price' => 48,
                    'lessons' => [
                        ['name' => 'Mettre en œuvre le style dans l’assiette', 'price' => 26],
                        ['name' => 'Harmoniser un repas à quatre plats', 'price' => 26],
                    ],
                ],
            ],
        ];

        // Parcourir les thèmes et insérer en base
        foreach ($themesData as $themeName => $cursusList) {
            $theme = new Theme();
            $theme->setName($themeName);
            $manager->persist($theme);

            foreach ($cursusList as $cursusData) {
                $cursus = new Cursus();
                $cursus->setName($cursusData['name']);
                $cursus->setPrice($cursusData['price']);
                $cursus->setTheme($theme);
                $manager->persist($cursus);

                foreach ($cursusData['lessons'] as $lessonData) {
                    $lesson = new Lesson();
                    $lesson->setName($lessonData['name']);
                    $lesson->setPrice($lessonData['price']);
                    $lesson->setCursus($cursus);
                    $manager->persist($lesson);
                }
            }
        }

        // Sauvegarder les données en base
        $manager->flush();
    }
}

