<?php

namespace App\Form;

use App\Entity\Category;
use App\Data\FilterData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('min', NumberType::class,[
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix Min.'
            ]
        ])
        ->add('max', NumberType::class,[
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Prix Max.'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Filtrer par Prix',
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'allow_extra_fields' => true,
            'method' => 'GET',   // on veut passer les paramètres dans l'url pour partager les recherches
            'csrf_protection' => false, // pas de risques lors d'une recherche
        ]);
    }

}
