<?php

namespace App\Form;

use App\Entity\Fruit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FavoriteFruitType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        $builder->add('name', EntityType::class, [
            'label' => 'Select names',
            'class' => Fruit::class,
            'choice_label' => 'name',
            'multiple' => true
         ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Save Favorites',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fruits');
        $resolver->setAllowedTypes('fruits', 'array');
        $resolver->setDefaults([
            'data_class' => Fruit::class,
        ]);
    }
}