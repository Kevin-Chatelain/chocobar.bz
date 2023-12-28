<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'maxlength' => '50'
                ],
                'label' => 'Nom et prénom',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'required' => false,
                'label' => 'Pseudo',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'minlength' => '2',
                    'maxlength' => '180'
                ],
                'label' => 'Adresse E-mail',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe'
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ]) 
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
