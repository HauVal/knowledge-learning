<?php

namespace App\Form;

use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form class for the Theme entity.
 * This form allows users to add or edit a theme.
 */
class ThemeType extends AbstractType
{
    /**
     * Builds the form fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options Additional options for form customization
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom', // French label for "Name"
        ])
        ;
    }

    /**
     * Configures options for this form.
     *
     * @param OptionsResolver $resolver The resolver for form options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
        ]);
    }
}

