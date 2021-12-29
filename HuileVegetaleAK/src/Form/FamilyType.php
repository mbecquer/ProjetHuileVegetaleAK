<?php

namespace App\Form;

use App\Entity\Family;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FamilyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => false,
            'required' => true,
            // 'help' => '(Saisir en majuscules)',
            "attr" => [
                'placeholder' => "Nom de la famille",

            ]
        ])
        ->add('active', CheckboxType::class, [
            'required' => false
        ])
        ->add('story', TextareaType::class, [
            'label' => false,
            'required' => false,
            "attr" => [
                'rows' => 15,
                'cols' => 20,
                'placeholder' => 'Saisir l\'histoire de la famille',
                'style' => "resize:none; width:100%;border:none;outline:none;",

            ]
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Family::class,
        ]);
    }
}
