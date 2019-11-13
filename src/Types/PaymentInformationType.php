<?php
// src/Models/PaymentInformation.php

namespace App\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentInformationType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', HiddenType::class)
            ->add('creditCardNumber', TextType::class,[ 'trim' => true,])
            ->add('name', TextType::class)
            ->add('expirationDate',  DateType::class, ['widget' => 'single_text',  'format' => 'MM/yy'])
            ->add('ccv', TextType::class)
            ->add('orderRef', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }
}