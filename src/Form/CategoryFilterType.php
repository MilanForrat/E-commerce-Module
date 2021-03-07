<?php

namespace App\Form;

use App\Data\CategoryData;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class CategoryFilterType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('categories', EntityType::class,[
            'label' => false,
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'class' => Category::class,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Filtrer par Catégorie'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}