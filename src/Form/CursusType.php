<?php

namespace App\Form;

use App\Entity\Cursus;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form class for creating and editing Cursus entities.
 */
class CursusType extends AbstractType
{
    /**
     * Builds the form for the Cursus entity.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array $options The form options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom', // Course name
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix', // Course price
            ])
            ->add('theme', EntityType::class, [
                'label' => 'Thèmes', // Theme selection
                'class' => Theme::class,
                'choice_label' => 'name',
            ]);
    }

    /**
     * Configures the options for the Cursus form.
     *
     * @param OptionsResolver $resolver The resolver for the form options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cursus::class,
        ]);
    }
}

