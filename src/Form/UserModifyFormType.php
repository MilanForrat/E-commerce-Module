<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserModifyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TypeTextType::class, [
                'label' => 'Votre Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ])],
                'attr' => [
                    'placeholder' => 'Saisissez votre prénom'
                ]
            ])
            ->add('lastname',  TypeTextType::class, [
                'label' => 'Votre Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ])],
                'attr' => [
                    'placeholder' => 'Saisissez votre nom'
                ]
            ])
            ->add('road_number', TextareaType::class, [
                'label' => 'Numéro de voie',
                'attr' => [
                    'placeholder' => 'N°...',
                    'rows' => 1,
                ]
            ])
            ->add('road', TypeTextType::class, [
                'label' => 'Nom de la voie',
                'attr' => [
                    'placeholder' => 'Saisissez le nom de la voie'
                ]
            ])
            ->add('postal_code', TextareaType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'rows' => 1,
                    'placeholder' => 'Saisissez le code postal',
                ]
            ])
            ->add('city', TypeTextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Saisissez la ville',
                ]
            ])
            ->add('phone_number', TextareaType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'rows' => 1,
                    'placeholder' => 'Saisissez votre numéro de téléphone',
                ]
            ])
            ->add('receipt_address', TextareaType::class, [
                'label' => 'Précisez votre adresse de facturation',
                'attr' => [
                    'rows' => 1,
                    'placeholder' => 'Saisissez votre adresse de facturation complète',
                ]
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
