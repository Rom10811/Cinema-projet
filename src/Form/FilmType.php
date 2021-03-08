<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom',TextType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('Description',TextType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('DateSortie', DateType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('Image', FileType::class,
            [
                'required' => true,
                'multiple' =>false,
                'mapped' => false,
                'label'=> 'Ajout une affiche'
            ])
            ->add('Video', FileType::class,
            [
                'required' => true,
                'multiple' => false,
                'mapped' => false,
                'label' => 'Ajouter une bande annonce'
            ])
            ->add('DateMinDiffusion',DateType::class,
            [
                "attr" =>[
                    "class"=>"form-control"
                ],
                'label' => 'Date minimum de diffusion'
            ])
            ->add('DateMaxDiffusion', DateType::class,
            [
                "attr" => [
                    "class" => "form-control"
                ],
                'label' => 'Date maximale de diffusion'
            ])
            ->add('Tags', EntityType::class,[
                'multiple' => true,
                "class" => Tags::class,
                "attr" => [
                    "class" => "select-tags form-control"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
