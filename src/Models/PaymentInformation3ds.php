<?php
// src/Models/PaymentInformation3ds.php

namespace App\Models;
use Symfony\Component\Validator\Constraints as Assert;


class PaymentInformation3ds
{
    protected $amount;
   
   
    protected $creditCardNumber;
   
     /**
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Votre nom doit etre supperieurX a  {{ limit }} caracteres.",
     *      maxMessage = "Votre nom doit etre inferieurX a  {{ limit }} characters"
     * )
     */
     protected $name;
   
     protected $expirationDate;
   
     protected $ccv;
   
     protected $orderRef;

    public function getAmount()
    {
        return $this->amount;        
    }
    public function setAmount($amount)
    {
        $this->amount = $amount *100;
    }
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;        
    }

    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;
    }

    public function getName()
    {
        return $this->name;        
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;        
    }

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
    
    public function getCcv()
    {
        return $this->ccv;        
    }

    public function setCcv($ccv)
    {
        $this->ccv = $ccv;
    }

    
    public function getOrderRef()
    {
        return $this->orderRef;        
    }

    public function setOrderRef($orderRef)
    {
        $this->orderRef = $orderRef;
    }
}