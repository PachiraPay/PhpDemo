<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Twig\Environment; 

use App\Models\PaymentInformation;
use App\Types\PaymentInformationType;
use App\Models\PaymentOption;
use App\Types\PaymentOptionType;
use App\Services\PaymentServices;
use App\Services\PaymentOptionServices;
use Psr\Log\LoggerInterface;


class PaymentOptionController extends AbstractController
{
  
    /** @var PaymentServices $paymentServices paymentservice from Cpayment. */
    protected $paymentServices = null;
  
    /** @var PaymentOptionServices $paymentOptionServices paymentOptionservice from Cpayment. */
    protected $paymentOptionServices = null;

    /** @var int $MerchantId description. */
    protected $merchantId;

    /** @var string $MerchantSiteId description. */
    protected $merchantSiteId;

      
   /**
    * Class constructor.
    */
   public function __construct($merchantId, $merchantSiteId, PaymentServices $paymentServices, PaymentOptionServices $paymentOptionServices)
   {
     $this->paymentServices = $paymentServices;
     $this->merchantId = $merchantId;
     $this->merchantSiteId = $merchantSiteId;
     $this->paymentOptionServices = $paymentOptionServices;
   }

   

   /**
   * @Route("/optionPaiment3", name="optionpaiment_real_posted")
   */
  public function IndexPaymentOptionPosted(LoggerInterface $logger, Request $request)
  {
    $payForm = new PaymentOption();
    $form = $this->createForm(PaymentOptionType::class, $payForm);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      try   
      {
        $result =  $this->paymentOptionServices->GetPaymentOption($payForm);
        return $this->render('PaymentOption/result.html.twig', [
          'result' => $result,
      ]);
      } catch (Exception $e) {
        //$result = $e->getMessage();
      }
    }
    else {
      $payForm->setMerchantId($this->merchantId);
      $payForm->setMerchantSiteId($this->merchantSiteId);
      $form = $this->createForm(PaymentOptionType::class, $payForm);
      return $this->render('PaymentOption/formsop.html.twig', [
        'form' => $form->createView(),
    ]);
    }
}

  /**
   * @Route("/optionPaiment", name="optionpaiment")
   */
   public function Index()
  {
            $payForm = new PaymentInformation();
        $form = $this->createFormBuilder($payForm)
        ->add('amount', TextType::class, ['required' => true, 
        'label' => 'Montant',
        'attr' => ['placeholder'=>"100"]])
        ->add('creditCardNumber', NumberType::class, ['required' => true, 
        'label' => 'Numéro de Carte',
        'attr' => ['placeholder'=>"Numéro de Carte"]])
        ->add('name', TextType::class, ['required' => true,
         'label' => 'Nom et Prénom',
         'attr' => ['placeholder'=>"Prénom Nom"]])
        ->add('expirationDate',  DateType::class, ['required' => true, 
          'label' => 'Date d\'expiration',
          'widget' => 'single_text',
          'format' => 'MM/yy',
           'attr' => ['placeholder'=>"mm/yy"]])
        ->add('ccv', TextType::class, ['required' => true,
         'label' => 'CCV',
         'attr' => ['placeholder'=>"xxx"]])
        ->add('orderRef', TextType::class, ['required' => true,
           'label' => 'Order REF',
           'attr' => ['placeholder'=>"Order_REF"] ])
        ->add('save', SubmitType::class, ['label' => 'Payer'])
        ->getForm();
  



        return $this->render('PaymentOption/forms.html.twig', [
          'form' => $form->createView(),
      ]);
  }
    /**
   * @Route("/optionPaiment2", name="optionpaiment2",  methods="GET")
   */
  public function IndexBis()
  {
        $payForm = new PaymentInformation();
        $form = $this->createForm(PaymentInformationType::class, $payForm);
        return $this->render('PaymentOption/forms2.html.twig', [
          'form' => $form->createView(),
      ]);
  }

  /**
   * @Route("/optionPaiment2", name="optionpaiment2_form_posted", methods="POST")
   */
  public function Pay(LoggerInterface $logger, Request $request)
  {
    $payForm = new PaymentInformation();
       

    $form = $this->createForm(PaymentInformationType::class, $payForm);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
  
      try
      {
        $paymentInformation = $form->getData();
      print_r($paymentInformation);

        $result = $this->paymentServices->PushPayment($paymentInformation);
    
      } catch (Exception $e) {
        $result = $e->getMessage();
      }
    print($result);
    return $this->render('CardPayment/result.html.twig',['result' => $result]);
  }
  else{
    return $this->render('PaymentOption/forms2.html.twig', [
      'form' => $form->createView(),
  ]);
  }
  }

}