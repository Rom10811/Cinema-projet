<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "attr"=>
                [
                    "class" => "form-control",
                    "placeholder" => "Entrer le mail pour lequel le mot de passe va être modifié"
                ]
            ])
            ->add('Envoyer', SubmitType::class, [
                "attr" =>
                [
                    "class" => "form-control"
                ],
                'label' => 'Envoyer la demande'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
