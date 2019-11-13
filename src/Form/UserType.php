<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label'=>'Prénom'])
            ->add('lastname', TextType::class, ['label'=>'Nom'])
            ->add('email',TextType::class, ['label'=>'Adresse mail'])
            ->add('address',TextType::class, ['label'=>'Adresse'])
            ->add('zipcode',NumberType::class, ['label'=>'Code Postal'])
            ->add('city',TextType::class, ['label'=>'Ville'])
            ->add('country',TextType::class, ['label'=>'Pays'])
            ->add('phoneNumber',TextType::class, ['label'=>'Téléphone'])
            ->add('role', ChoiceType::class, ['choices'=> ['Administrateur' => 'ROLE_ADMIN', 'Client'=> 'ROLE_USER',]])
            ->add('password', PasswordType::class,  ['label'=>'Mot de Passe'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
