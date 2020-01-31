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
use App\Services\Payment3dsServices;
use App\Types\PaymentInformationType;


class Card3DsPaymentController extends AbstractController
{
  /** @var Payment3dsServices $paymentService3ds paymentservice from Pachirapay. */ 
  protected $paymentService3ds = null;
  
  /**
   * Class constructor.
   */
  public function __construct(Payment3dsServices $payment3dsServices)
  {
    $this->paymentService3ds = $payment3dsServices;
  }

    /**
   * @Route("/CardPayment3ds", name="CardPayment3ds_form")
   */
  public function Index(LoggerInterface $logger, Request $request)
  {
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
    "https" : "http") . "://" . $_SERVER['HTTP_HOST'] ;

    $payForm = new PaymentInformation();

    $form = $this->createForm(PaymentInformationType::class, $payForm);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) 
    {
      try
      {
        $paymentInformation = $form->getData();
        $result = $this->paymentService3ds->InitPayment3DS($paymentInformation);
        $_SESSION["paymentRequestId"] = $result["card3ds_payment_request_id"];
      } catch (Exception $e) {
        $result = $e->getMessage();
      }
      return $this->render('CardPayment3ds/InitPaiement3DsForm.html.twig',['result' => $result]);
    }
    else{
    $amountvar = rand(1,100000)/100;
    $payForm->setAmount($amountvar);
    $form = $this->createForm(PaymentInformationType::class, $payForm);
    return $this->render('CardPayment3ds/Paiement3DsForm.html.twig', [
      'form' => $form->createView()]);
    }
  }

  /**
   * @Route("/CardPayment3dsPut", name="CardPayment3ds_Put")
   */
  public function Return3ds(LoggerInterface $logger, Request $request)
  {
    try
      {
        $result = $this->paymentService3ds->FinalisePayment3DS(33,"99002",$_SESSION["paymentRequestId"],$_SESSION["orderRef"],"LabelTag");
      } catch (Exception $e) {
        $result = $e->getMessage();
      }

    return $this->render('CardPayment3ds/FinalisePaiement3DsForm.html.twig',['result' => $result]);
  }

}