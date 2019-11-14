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


class Card3DsPaymentController extends AbstractController
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
    
  }
}