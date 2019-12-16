<?php
// src/Models/PaymentInformation.php

namespace App\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentInformationType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', HiddenType::class,['attr' => ['id'=>"amountid" ]])
            ->add('creditCardNumber', TextType::class,[ 'trim' => true,
                                                        'required'=> true,
                                                        'attr' => ['placeholder'=>"XXXX XXXX XXXX XXXX",
                                                                   'pattern'=>"\\d{4} \\d{4} \\d{4} \\d{4}",
                                                                   'class'=>'masked',
                                                                   'oninput'=>"checkCardNumber(this)"]
                                                        ])
            ->add('name', TextType::class,['required'=> true,
                                            'attr' => ['placeholder'=>"Nom et Prénom",
                                                        'maxlength'=>"30",
                                                        'onkeyup'=>"this.value = this.value.toUpperCase();"]])
            ->add('expirationDate',  DateType::class, [ 'format'=>'MM/yy',
                                                        'widget'=>'single_text',
                                                        'attr' => ['placeholder'=>"MM/YY",
                                                        'class'=> "masked",
                                                        'data-valid-example'=>"11/18",
                                                        'pattern'=>"(1[0-2]|0[1-9])\\/\\d\\d"]
                                                        ])
            ->add('ccv', TextType::class, [ 'required'=> true,
                                            'attr' => ['placeholder'=>"xxx",
                                            'maxlength'=> "3",
                                            'title'=>"3 nombre minimum",
                                            'pattern'=>".{3,}"]
                                            ])
            // ->add('ccv', TextType::class)
            ->add('orderRef', TextType::class,[ 'required'=> true,
                                                'attr' => ['placeholder'=>"Order REF"]])
            ->add('save', SubmitType::class)
        ;
    }
}