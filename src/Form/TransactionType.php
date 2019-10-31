<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          
            ->add('quantity',NumberType::class, ['label'=>'Quantité'])
            ->add('type',ChoiceType::class, ['choices'=> ['Achat' => 'Achat', 'Vente'=> 'Vente',]])
            ->add('currency',TextType::class, ['label'=>'Cryptomonnaie'])
            ->add('user',TextType::class, ['label'=>'Cryptomonnaie'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}

