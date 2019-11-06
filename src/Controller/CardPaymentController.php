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
  //  /**
  //  * @Route("/CardPayment", name="CardPayment_form", methods="GET")
  //  */
  //  public function Index()
  // {
    
  //   return $this->render('CardPayment/formulaire.html.twig');
  // }

    /**
   * @Route("/CardPayment", name="CardPayment_form",  methods="GET")
   */
  public function IndexBis()
  {
        $payForm = new PaymentInformation();
        $form = $this->createForm(PaymentInformationType::class, $payForm);
        return $this->render('CardPayment/PaiementForm.html.twig', [
          'form' => $form->createView(),
      ]);
  }
 
   /**
   * @Route("/CardPayment", name="CardPayment_form_posted", methods="POST")
   */
  public function Pay(LoggerInterface $logger, Request $request)
  {
    $routeName = $request->attributes->get('_route');
    $routeParameters = $request->attributes->get('_route_params');

    // use this to get all the available attributes (not only routing ones):
    $allAttributes = $request->attributes->all();
    $logger->info('info formulaire => ',$allAttributes );
    
    $paymentInformation = new PaymentInformation();
    if($request->request->has("montant"))
    {
      $paymentInformation->setAmount($request->request->getInt("montant"));
    }
    if($request->request->has("creditCard"))
    {
      $paymentInformation->setCreditCardNumber($request->request->getAlnum("creditCard"));
    }
    if($request->request->has("nomPrenom"))
    {
      $paymentInformation->setName($request->request->getAlpha("nomPrenom"));
    }
    if($request->request->has("dateExp"))
    {
      $paymentInformation->setExpirationDate($request->request->get("dateExp"));
    }
    if($request->request->has("cvc"))
    {
      $paymentInformation->setCcv($request->request->getDigits("cvc"));
    }   
     if($request->request->has("orderRef"))
    {
      $paymentInformation->setOrderRef($request->request->get("orderRef"));
    }
  //$logger->info('info formulaire => ',);
    try
    {
    print_r($paymentInformation);

      $result = $this->paymentServices->PushPayment($paymentInformation);
  
    } catch (Exception $e) {
      $result = $e->getMessage();
    }
    print($result);
    return $this->render('CardPayment/result.html.twig',['result' => $result]);
 
  }

}