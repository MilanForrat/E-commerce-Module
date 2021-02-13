<?php
namespace App\Form;

use App\Entity\Category;
use App\Data\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,  // on précise la classe 
                'expanded' => true,    // permet les checkbox
                'multiple' => true,    // permet les checkbox
            ])
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
            ->add('promo', CheckboxType::class,[
                'label' => 'En promotion',
                'required' => false,
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false, // pas de risques lors d'une recherche
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; 
    }
}