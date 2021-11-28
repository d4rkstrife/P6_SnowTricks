<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\FigureGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => false
            ])
            ->add('figureGroup', EntityType::class, [
                // looks for choices from this entity
                'class' => FigureGroup::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'Groupe',

                // used to render a select box, check boxes or radios
                //'multiple' => true
                //'expanded' => true

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
