<?php
// src/Models/PaymentInformation.php

namespace App\Services;


use GuzzleHttp\Client;
use App\Models\PaymentInformation;
use pachirapay\ApiException;
use pachirapay\Configuration;

use pachirapay\Api\Card3DsPaymentApi;
use pachirapay\Api\SecurityTokenApi;
use pachirapay\Model\Card3DsCheckEnrollmentRequest;
use pachirapay\Model\Card3DsValidateAuthenticationAndAuthorizeRequest;
use pachirapay\Model\CardPaymentContextData;
use pachirapay\Model\Options;
use pachirapay\Model\Order;
use pachirapay\Model\CardData;
use pachirapay\Model\StoredCard;
use pachirapay\Model\ValidationModeOverride;

class Payment3dsServices
{
    /** @var type $ description. */
    protected $securityTokenApi = null;

    /** @var Card3DsPaymentApi $card3DsPaymentApi card3DsPaymentApi from Pachirapay. */
    protected $card3DsPaymentApi = null;
    
    /** @var int $MerchantId description. */
    protected $merchantId; 

    /** @var string $MerchantSiteId description. */
    protected $merchantSiteId;

    /** @var Configuration $configuration Configuration. */
    protected  $configuration ;
    
    protected $token = null;
     /**
     * Class constructor.
     */
    public function __construct($merchantId, $merchantSiteId, $url)
    {
        $configuration = new Configuration();
        $configuration->setHost($url);
        $this->securityTokenApi = new SecurityTokenApi( new Client(), $configuration);
        $this->card3DsPaymentApi = new Card3DsPaymentApi( new Client(), $configuration);
        $this->merchantId = $merchantId;
        $this->merchantSiteId = $merchantSiteId;
    }
 
    public function InitPayment3DS(PaymentInformation $paymentInformation)
    {
        $this->GetToken();
        $card3DsCheckEnrollmentRequest = $this->ConvertToCard3DsPaymentRequest($paymentInformation);
        return  $this->card3DsPaymentApi->v1PaymentsCard3dsPaymentPost($this->GetToken(), $card3DsCheckEnrollmentRequest);
    }

    public function FinalisePayment3DS($merchantId,$merchantSiteId,$paymentRequestId,$orderRef,$labelTag)
    {
        $this->GetToken();
        $card3Ds_Payment_PutRequest = new Card3DsValidateAuthenticationAndAuthorizeRequest(); 

        $card3Ds_Payment_PutRequest->setMerchantId($merchantId);
        $card3Ds_Payment_PutRequest->setMerchantSiteId($merchantSiteId);
        $card3Ds_Payment_PutRequest->setPaymentRequestId($paymentRequestId);
        $card3Ds_Payment_PutRequest->setOrderRef($orderRef);
        $card3Ds_Payment_PutRequest->setOrderTag($labelTag);
        
        return  $this->card3DsPaymentApi->v1PaymentsCard3dsPaymentPut($this->GetToken(), $card3Ds_Payment_PutRequest);
    }


    function GetToken()
    {
        if($this->token == null)
        {
            $authorization = 'dG5yOnRucjEyMzQq'; // string | The credentials (login:password in base64)
            $auth_token = $this->securityTokenApi->v1AuthTokenGet($authorization);
            $auth_token = trim($auth_token,'"');
            $this->token = $auth_token ;
        }
        return $this->token ;
    }

    function ConvertToCard3DsPaymentRequest(PaymentInformation $paymentInformation)
    {
        //Get current url of current page
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] ;
        
        $card3ds_payment_request = new Card3DsCheckEnrollmentRequest(); 
        $card3ds_payment_request->setReturnUrl($link."/CardPayment3dsPut");
        $context = new CardPaymentContextData();
        $context->setMerchantId(33);//lecture depuis form
        $context->setMerchantSiteId("99002");
        $context->setCurrency("eur");
        $context->setCountry("fr");
        $context->setPaymentOptionRef("21");
        $context->setCustomerRef("CUSTOMER01");
        $context->setPaymentAttempt(1);
    
        $option = new Options();
    
        $order = new Order();
        $order->setOrderRef($paymentInformation->getOrderRef());
        $_SESSION["orderRef"] = $paymentInformation->getOrderRef();
        $order->setInvoiceId(12345);
        $order->setOrderDate(date("Y/m/d H:i:s"));
        $order->setAmount($paymentInformation->getAmount() *100);
    
        $CardData = new CardData();
        $CardData->setCardScheme("cb");
        $CardData->setExpirationDate($paymentInformation->getExpirationDate());
        $CardData->setCardNumber(str_replace(' ', '', $paymentInformation->getCreditCardNumber()));
        $CardData->setSecurityNumber($paymentInformation->getCcv());
        $CardData->setCardLabel($paymentInformation->getName());

    
        $validationMode = new ValidationModeOverride();
        $validationMode->setValidationMode("manual");
    
        $card3ds_payment_request->setContext($context);
        $card3ds_payment_request->setOptions($option);
        $card3ds_payment_request->setOrder($order);
        $card3ds_payment_request->setCard($CardData);
        $card3ds_payment_request->setValidationMode($validationMode);
        return $card3ds_payment_request;
    }
}