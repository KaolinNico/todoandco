<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\Entity\User',
                'role' => ['ROLE_USER']
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'label' => "Nom d'utilisateur"
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                    'required' => true,
                    'first_options' => [
                        'attr' => ['class' => 'form-control'],
                        'label' => 'Mot de passe'
                    ],
                    'second_options' => [
                        'attr' => ['class' => 'form-control'],
                        'label' => 'Tapez le mot de passe à nouveau'
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Adresse email'
                ]
            );
        if (in_array('ROLE_ADMIN', $options['role'])) {
            $builder->add(
                'roles',
                ChoiceType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'choices' =>
                        [
                            'Administrateur' => 'ROLE_ADMIN',
                            'Utilisateur' => 'ROLE_USER'
                        ],
                    'multiple' => true,
                    'required' => true,
                ]
            );
        }
    }
}
