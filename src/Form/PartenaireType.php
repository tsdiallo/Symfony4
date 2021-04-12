<?php

namespace App\Form;

use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rs',TextType::class,[
                'required'=>false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nci',TextType::class,[
                'required'=>false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom',TextType::class,[
                'required'=>false,
                'mapped' => false
            ])
            ->add('prenom',TextType::class,[
                'required'=>false,
                'mapped' => false
            ])
            ->add('login',TextType::class,[
                'required'=>false,
                'mapped' => false
            ])
            ->add('password',PasswordType::class,[
                'required'=>false,
                'mapped' => false
            ])
            ->add('etat',ChoiceType::class, [
                'required'=>false,
                'mapped' => false,
                'choices'  => [
                    'Actif' => "Actif",
                    'Inactif' => 'Inactif',
                  
                ]
            ]
            
            )
            ->add('solde',NumberType::class,[
                'required'=>false,
                'mapped' => false
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
