<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

/**
 * Class UserType
 *
 * This form is used for creating and editing users in the application.
 */
class UserType extends AbstractType
{
    /**
     * Builds the user form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom', // User's name
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email', // User's email
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe', // Password field
                'mapped' => false, // Not mapped to the entity to allow optional password change
                'required' => false, 
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle :', // User role selection
                'choices' => [
                    'Utilisateur' => 'ROLE_USER', // Standard user
                    'Administrateur' => 'ROLE_ADMIN', // Admin user
                ],
                'expanded' => false, // Displayed as a dropdown
                'multiple' => false, // Only one role can be selected
                'mapped' => false, // Not directly mapped to the entity
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer', // Submit button
                'attr' => ['class' => 'btn btn-success'],
            ]);
    }

    /**
     * Configures options for this form.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Links this form to the User entity
        ]);
    }
}


