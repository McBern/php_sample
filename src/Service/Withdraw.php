<?php

declare(strict_types = 1);

namespace Payservice;

use Payservice\CurrencyRate;
use Payservice\ClientType;
use Payservice\Utils;

class Withdraw {
	private $amount;
	private $currency;
	private $chargePercentage;
	private $clientType;
	private $clientID;
	private $transactionDate;


	public function __construct(float $amount=NULL, string $currency=NULL, ClientType $clientType=NULL)
    {
    	$chargePercentage = new Rules();
        $this->amount = $amount==NULL ? 0 : $amount;
        $this->currency = $currency==NULL ? "EUR" : $currency;
        $this->clientType = $clientType == NULL ? ClientType::PrivateClient : $clientType;

        if($clientType==ClientType::PrivateClient){
        	$this->chargePercentage = $chargePercentage->withdrawPrivatePercentage();
        }
        if($clientType==ClientType::BusinessClient){
        	$this->chargePercentage = $chargePercentage->withdrawBusinessPercentage();
        }
    }

	public function setAmount(float $amount){
        $this->amount = $amount;
        return $this;
    }

	public function getAmount(){
        return $this->amount;
    }

	public function setCurrency(string $currency){
        $this->currency = $currency;
        return $this;
    }

	public function getCurrency(){
        return $this->currency;
    }

	public function getClientType(){
        return $this->clientType;
    }

	public function setClientType(string $clientType){
        $this->clientType = $clientType;
    	$chargePercentage = new Rules();

        if($clientType==ClientType::PrivateClient){
        	$this->chargePercentage = $chargePercentage->withdrawPrivatePercentage();
        }
        if($clientType==ClientType::BusinessClient){
        	$this->chargePercentage = $chargePercentage->withdrawBusinessPercentage();
        }
        return $this;
    }

	public function setTransactionDate($transactionDate){
        $this->transactionDate = $transactionDate;
        return $this;
    }

	public function getTransactionDate(){
        return $this->transactionDate;
    }

	public function setClientID($clientID){
        $this->clientID = $clientID;
        return $this;
    }

	public function getClientID(){
        return $this->clientID;
    }

    public function calculateCommission(){
    	$commission = ($this->chargePercentage / 100) * $this->amount;
        return Utils::roundDecimalUp($commission,2);
    }


}