<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void  //les champs ili ena bhc n3amlhom 
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('nbBooks')
            ->add ('submit', SubmitType::class, [
                'label' => 'Create Author'
            ]  )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void //configurer les options du formulaire
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
