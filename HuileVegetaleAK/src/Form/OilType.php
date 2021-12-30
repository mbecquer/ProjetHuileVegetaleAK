<?php

namespace App\Form;


use App\Entity\Oil;
use App\Entity\Family;
use App\Repository\FamilyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('images', FileType::class, [
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
         
            ->add('family', EntityType::class, [
                'class' => Family::class,
                'choice_label' => function ($family) {
                    return $family->getName();
                },
                'expanded' => false,
                'label' => 'Famille',
                'query_builder' => function (FamilyRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.name', 'ASC');
                },
                'attr' => [
                    'class' => 'text-uppercase'
                ]

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
