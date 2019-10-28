<?php
// src/Models/PaymentInformation.php

namespace App\Models;


class PaymentOption
{
    protected $merchantId;
    protected $merchantSiteId;
 
    public function getMerchantId()
    {
        return $this->merchantId;        
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }
    public function getMerchantSiteId()
    {
        return $this->merchantSiteId;        
    }

    public function setMerchantSiteId($merchantSiteId)
    {
        $this->merchantSiteId = $merchantSiteId;
    }

   
}