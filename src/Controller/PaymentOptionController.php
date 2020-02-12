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
  
    /** @var PaymentServices $paymentServices paymentservice from Pachirapay. */
    protected $paymentServices = null;
  
    /** @var PaymentOptionServices $paymentOptionServices paymentOptionservice from Pachirapay. */
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
   * @Route("/optionPaiment", name="optionpaiment_real_posted")
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
        return $this->render('PaymentOption/PaymentOptionResult.html.twig', [
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
      return $this->render('PaymentOption/PaymentOptionForm.html.twig', [
        'form' => $form->createView(),
    ]);
    }
}

}