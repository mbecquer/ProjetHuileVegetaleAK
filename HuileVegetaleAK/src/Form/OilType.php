<?php

namespace App\Form;

use App\Entity\Oil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => false,
                "required" => true,
                "attr" => [
                    'placeholder' => "Nom de l'huile"
                ]
            ])
            ->add('description', TextareaType::class, [
                "label" => false,
                'required' => false,
                "attr" => [
                    'rows' => 5,
                    'cols' => 25,
                    'placeholder' => 'Description',
                    'style' => "resize:none; width:100%;border:none;outline:none;",
            
                    ]
            ])
            ->add('capacity', NumberType::class, [
                "label" => false,
                "attr" => [
                    'placeholder' => "Capacité"
                ],
                'help' => '(Préciser la capacité en millilitre)'
            ])
            ->add('price', NumberType::class, [
                "label" => false,
                "attr" => [
                    'placeholder' => "Prix"
                ],
                'help' => '(Préciser le prix en euros)'
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->add('created_at', DateType::class,[
                "label" => 'Date',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oil::class,
        ]);
    }
}
