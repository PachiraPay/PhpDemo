<?php
// src/Models/PaymentInformation.php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\PaymentOption;
use pachirapay\ApiException;
use pachirapay\Configuration;

use pachirapay\Api\PaymentOptionsApi;
use pachirapay\Api\CardPaymentApi;
use pachirapay\Api\SecurityTokenApi;
use pachirapay\Model\CardPaymentRequest;
use pachirapay\Model\CardPaymentContextData;
use pachirapay\Model\Options;
use pachirapay\Model\Order;
use pachirapay\Model\CardData;
use pachirapay\Model\StoredCard;
use pachirapay\Model\ValidationModeOverride;

class PaymentOptionServices
{
    /** @var type $ description. */
    protected $securityTokenApi = null;

    /** @var PaymentOptionsApi $PaymentOptionsApi PaymentOptionsApi from Pachirapay. */
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