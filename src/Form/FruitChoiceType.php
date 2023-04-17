<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FruitChoiceType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        $builder->add('favoriteFruits', ChoiceType::class, [
            'choices' => $options['choices'],
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [],
            'data_class' => null,
        ]);

        $resolver->setAllowedTypes('choices', 'array');
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}