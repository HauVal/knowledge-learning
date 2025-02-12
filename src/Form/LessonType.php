<?php

namespace App\Form;

use App\Entity\Cursus;
use App\Entity\Lesson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form class for creating and editing Lesson entities.
 */
class LessonType extends AbstractType
{
    /**
     * Builds the form fields for Lesson.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options for the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom', // Lesson name
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix', // Lesson price
            ])
            ->add('cursus', EntityType::class, [
                'class' => Cursus::class,
                'choice_label' => 'name', // Select a cursus by name
            ])
        ;
    }

    /**
     * Configures the form options.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class, // Binds the form to the Lesson entity
        ]);
    }
}

