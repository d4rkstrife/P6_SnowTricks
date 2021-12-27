<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\FigureGroup;
use App\Form\FigureVideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
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

            ])
            ->add('picture', FileType::class, [
                'label' => 'Picture (Png or jpeg)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the file
                // every time you edit the Product details
                'required' => false,
                'multiple' => true,
            ])
            ->add('relatedVideos', CollectionType::class, [
                'entry_type' => FigureVideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
