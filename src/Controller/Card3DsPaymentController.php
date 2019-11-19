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
use App\Services\PaymentServices3ds;
use App\Types\PaymentInformationType;


class Card3DsPaymentController extends AbstractController
{
  /** @var PaymentServices3ds $paymentService3ds paymentservice from Cpayment. */ 
  protected $paymentService3ds = null;
  
  /**
   * Class constructor.
   */
  public function __construct(PaymentServices3ds $paymentServices3ds)
  {
    $this->paymentService3ds = $paymentServices3ds;
  }

    /**
   * @Route("/CardPayment3ds", name="CardPayment3ds_form")
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
        $result = $this->paymentService3ds->PushPayment3Ds($paymentInformation);
        $_SESSION["paymentRequestId"] = $result["card3ds_payment_request_id"];
      } catch (Exception $e) {
        $result = $e->getMessage();
      }
      return $this->render('CardPayment3ds/result3ds.html.twig',['result' => $result]);
    }
    else{
    $amountvar = rand(1,100000)/100;
    $payForm->setAmount($amountvar);
    $form = $this->createForm(PaymentInformationType::class, $payForm);
    return $this->render('CardPayment3ds/PaimentForm3ds.html.twig', [
      'form' => $form->createView(),
      ]);
    }
  }

  /**
   * @Route("/CardPayment3dsPut", name="CardPayment3ds_Put")
   */
  public function Return3ds(LoggerInterface $logger, Request $request)
  {
    try
      {
        $result = $this->paymentService3ds->PushPayment3DsPut();
      } catch (Exception $e) {
        $result = $e->getMessage();
      }

    return $this->render('CardPayment3ds/result3dsPut.html.twig',['result' => $result]);
  }

}