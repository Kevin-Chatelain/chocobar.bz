<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]) 
            ->add('newPassword', RepeatedType::class, [
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ]
            ]) 
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier le mot de passe'
            ]);
    }
}
