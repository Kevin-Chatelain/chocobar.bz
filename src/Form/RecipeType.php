<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredients;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 1440
                ],
                'label' => 'Temps en minutes',
                'required' => false,
                'constraints' => [
                    new Assert\LessThan(1440),
                    new Assert\Positive()
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 1440
                ],
                'label' => 'Nombre de personnes',
                'required' => false,
                'constraints' => [
                    new Assert\LessThan(50),
                    new Assert\Positive()
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Difficulté',
                'required' => false,
                'constraints' => [
                    new Assert\LessThan(6),
                    new Assert\Positive()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix ',
                'required' => false,
                'constraints' => [
                    new Assert\LessThan(200),
                    new Assert\Positive()
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Favori ?',
                'required' => false,
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'name',
                'label' => 'Ingrédients utilisés',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer ma recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
