<?php
// src/Models/PaymentInformation.php

namespace App\Services;


use GuzzleHttp\Client;
use App\Models\PaymentInformation;
use CpaymentConnector\ApiException;
use CpaymentConnector\Configuration;

use CpaymentConnector\Api\Card3DsPaymentApi;
use CpaymentConnector\Api\SecurityTokenApi;
use CpaymentConnector\Model\Card3DsPaymentRequest;
use CpaymentConnector\Model\Card3DsPaymentPutRequest;
use CpaymentConnector\Model\CardPaymentContextData;
use CpaymentConnector\Model\Options;
use CpaymentConnector\Model\Order;
use CpaymentConnector\Model\CardData;
use CpaymentConnector\Model\StoredCard;
use CpaymentConnector\Model\ValidationModeOverride;

class PaymentServices3ds
{
    /** @var type $ description. */
    protected $securityTokenApi = null;

    /** @var Card3DsPaymentApi $card3DsPaymentApi card3DsPaymentApi from Cpayment. */
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
 
    public function PushPayment3Ds(PaymentInformation $paymentInformation)
    {
        $this->GetToken();
        $card3DsPaymentRequest = $this->ConvertToCard3DsPaymentRequest($paymentInformation);
        print_r($card3DsPaymentRequest);
        return  $this->card3DsPaymentApi->v1PaymentsCard3dsPaymentPost($this->GetToken(), $card3DsPaymentRequest);
    }

    public function PushPayment3DsPut()
    {
        $this->GetToken();
        $card3Ds_Payment_PutRequest = new Card3DsPaymentPutRequest(); 

        $card3Ds_Payment_PutRequest->setMerchantId(1);
        $card3Ds_Payment_PutRequest->setMerchantSiteId("100");
        $card3Ds_Payment_PutRequest->setPaymentRequestId($_SESSION["paymentRequestId"] );
        $card3Ds_Payment_PutRequest->setOrderRef($_SESSION["orderRef"]);
        $card3Ds_Payment_PutRequest->setOrderTag("LabelTag");
        
        return  $this->card3DsPaymentApi->v1PaymentsCard3dsPaymentPut($this->GetToken(), $card3Ds_Payment_PutRequest);
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

    function ConvertToCard3DsPaymentRequest(PaymentInformation $paymentInformation)
    {
        $card3ds_payment_request = new Card3DsPaymentRequest(); 

        $card3ds_payment_request->setReturnUrl("http://cpayment:8080/CardPayment3dsPut");
        $context = new CardPaymentContextData();
        $context->setMerchantId($this->merchantId);//lecture depuis form
        $context->setMerchantSiteId($this->merchantSiteId);
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
    
        $StoredCard = new StoredCard();
        $StoredCard->setId("");
        $StoredCard->setLabel("");
    
        $validationMode = new ValidationModeOverride();
        $validationMode->setValidationMode("manual");
    
        $card3ds_payment_request->setContext($context);
        $card3ds_payment_request->setOptions($option);
        $card3ds_payment_request->setOrder($order);
        $card3ds_payment_request->setCard($CardData);
        //$card3ds_payment_request->setStoredCard($StoredCard);
        $card3ds_payment_request->setValidationMode($validationMode);
        $card3ds_payment_request->setNotificationUrl("http://merchant.com/notification");
        return $card3ds_payment_request;
    }
}