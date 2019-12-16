<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Twig\Environment; 

use Psr\Log\LoggerInterface;

use App\Models\PaymentInformation;
use App\Services\PaymentServices;
use App\Types\PaymentInformationType;


class CardPaymentController extends AbstractController
{
  /** @var PaymentServices $paymentServices paymentservice from Cpayment. */ 
  protected $paymentServices = null;
  
  /**
   * Class constructor.
   */
  public function __construct(PaymentServices $paymentServices)
  {
    $this->paymentServices = $paymentServices;
  }

    /**
   * @Route("/CardPayment", name="CardPayment_form")
   */
  public function Index(LoggerInterface $logger, Request $request)
  {
    $payForm = new PaymentInformation();

    $form = $this->createForm(PaymentInformationType::class, $payForm);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) 
    {
      try
      {
        $paymentInformation = $form->getData();
        $result = $this->paymentServices->PushPayment($paymentInformation);
      } catch (Exception $e) {
        $result = $e->getMessage();
      }
      return $this->render('CardPayment/PaiementResultForm.html.twig',['result' => $result]);
    }
    else 
    {
      $amountvar = rand(1,100000)/100;
      $payForm->setAmount($amountvar);
      $form = $this->createForm(PaymentInformationType::class, $payForm);
      return $this->render('CardPayment/PaiementForm.html.twig', [
        'form' => $form->createView()]);
        
    }
  }
}