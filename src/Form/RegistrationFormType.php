<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('pseudo',TextType::class,[
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('nom',TextType::class,[
                "required" => false,
                "attr" =>[
                    "class" => "form-control",
                    'required' => false
                ]
            ])
            ->add('prenom',TextType::class,[
                "required" => false,
                "attr" =>[
                    "class" => "form-control",
                    'required' => false
                ]
            ])
            ->add('age',IntegerType::class,[
                "required" => false,
                "attr" =>[
                    "class" => "form-control",
                ]
            ])
            ->add('telephone', NumberType::class, [
                "required" => false,
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs doivent correspondre',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe',"attr" =>["class" => "form-control"]],
                'second_options' => ['label' => 'Confirmer le mot de passe',"attr" =>["class" => "form-control"]],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe'
                    ]),
                ],
            ])
            ->add('button', SubmitType::class,[
                'label' => 'Inscription',
                "attr" =>[
                    "class" => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
