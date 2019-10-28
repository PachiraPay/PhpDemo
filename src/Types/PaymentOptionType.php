<?php
// src/Models/PaymentOptionType.php

namespace App\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentOptionType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('merchantId', NumberType::class)
            ->add('merchantSiteId', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }
}