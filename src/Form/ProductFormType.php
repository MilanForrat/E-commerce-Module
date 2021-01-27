<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class, [
                'label' => 'Description rapide (carte) du produit'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description complète du produit'
            ])
            ->add('image', TextType::class)
            ->add('promo', IntegerType::class, [
                'label' => 'Le produit est en promotion ?'
            ])
            ->add('promo_price', IntegerType::class, [
                'label' => 'Prix si promotion'
            ])
            ->add('status', IntegerType::class, [
                'label' => 'Le produit est visible ?'
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Le produit est en stock ?'
            ])
            ->add('quantity', IntegerType::class)
            ->add('categories', EntityType::class,[
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
