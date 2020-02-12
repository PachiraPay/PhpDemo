<?php
// src/Models/PaymentInformation.php

namespace App\Services;


use GuzzleHttp\Client;
use App\Models\PaymentInformation;
use pachirapay\ApiException;
use pachirapay\Configuration;

use pachirapay\Api\CardPaymentApi;
use pachirapay\Api\SecurityTokenApi;
use pachirapay\Model\CardPaymentRequest;
use pachirapay\Model\CardPaymentContextData;
use pachirapay\Model\Options;
use pachirapay\Model\Order;
use pachirapay\Model\CardData;
use pachirapay\Model\StoredCard;
use pachirapay\Model\ValidationModeOverride;

class PaymentServices
{
    /** @var type $ description. */
    protected $securityTokenApi = null;

    /** @var CardPaymentApi $cardPaymentApi cardPaymentApi from Pachirapay. */
    protected $cardPaymentApi = null;
    
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
        $this->cardPaymentApi = new CardPaymentApi( new Client(), $configuration);
        $this->merchantId = $merchantId;
        $this->merchantSiteId = $merchantSiteId;
    }
 
    public function PushPayment(PaymentInformation $paymentInformation)
    {
        $this->GetToken();
        $cardPaymentRequest = $this->ConvertToCardPaymentRequest($paymentInformation);
        return  $this->cardPaymentApi->v1PaymentsCardPaymentPost($this->GetToken(), $cardPaymentRequest);
    }

    function GetToken()
    {
        if($this->token == null)
        {
            $authorization = 'Y2Rpc2NvdW50OmNkaXNjb3VudDEyMzQq'; // string | The credentials (login:password in base64)
            $auth_token = $this->securityTokenApi->v1AuthTokenGet($authorization);
            $auth_token = trim($auth_token,'"');
            $this->token = $auth_token ;
        }
        return $this->token ;
    }

    function ConvertToCardPaymentRequest(PaymentInformation $paymentInformation)
    {
        $card_payment_request = new CardPaymentRequest(); 
        $context = new CardPaymentContextData();
        $context->setMerchantId($this->merchantId);//lecture depuis form
        $context->setMerchantSiteId($this->merchantSiteId);
        $context->setCurrency("eur");
        $context->setCountry("fr");
        $context->setPaymentOptionRef("1");
        $context->setCustomerRef("CUSTOMER01");
        $context->setPaymentAttempt(1);
    
        $option = new Options();
    
        $order = new Order();
        $order->setOrderRef($paymentInformation->getOrderRef());
        $order->setInvoiceId(12345);
        $order->setOrderDate(date("Y/m/d H:i:s"));
        $order->setAmount($paymentInformation->getAmount()*100);
            
        $CardData = new CardData();
        $CardData->setCardScheme("cb");
        $CardData->setExpirationDate($paymentInformation->getExpirationDate());
        $CardData->setCardNumber(str_replace(' ', '', $paymentInformation->getCreditCardNumber()));
        $CardData->setSecurityNumber($paymentInformation->getCcv());
        $CardData->setCardLabel($paymentInformation->getName());
    
        $validationMode = new ValidationModeOverride();
        $validationMode->setValidationMode("manual");
    
        $card_payment_request->setContext($context);
        $card_payment_request->setOptions($option);
        $card_payment_request->setOrder($order);
        $card_payment_request->setCard($CardData);
        $card_payment_request->setValidationMode($validationMode);
        return $card_payment_request;
    }
}