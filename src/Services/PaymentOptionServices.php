<?php
// src/Models/PaymentInformation.php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\PaymentOption;
use CpaymentConnector\ApiException;
use CpaymentConnector\Configuration;

use CpaymentConnector\Api\PaymentOptionsApi;
use CpaymentConnector\Api\CardPaymentApi;
use CpaymentConnector\Api\SecurityTokenApi;
use CpaymentConnector\Model\CardPaymentRequest;
use CpaymentConnector\Model\CardPaymentContextData;
use CpaymentConnector\Model\Options;
use CpaymentConnector\Model\Order;
use CpaymentConnector\Model\CardData;
use CpaymentConnector\Model\StoredCard;
use CpaymentConnector\Model\ValidationModeOverride;

class PaymentOptionServices
{
    /** @var type $ description. */
    protected $securityTokenApi = null;

    /** @var PaymentOptionsApi $PaymentOptionsApi PaymentOptionsApi from Cpayment. */
    protected $paymentOptionsApi = null;
    
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
        $this->paymentOptionsApi = new PaymentOptionsApi( new Client(), $configuration);
        $this->merchantId = $merchantId;
        $this->merchantSiteId = $merchantSiteId;
    }
 
    public function GetPaymentOption(PaymentOption $paymentOption)
    {
        $this->GetToken();
        return  $this->paymentOptionsApi->v1PaymentOptionsMerchantsByMerchantIdSitesByMerchantSiteIdGet( $paymentOption->GetMerchantId(), $paymentOption->GetMerchantSiteId(), $this->GetToken());
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
}