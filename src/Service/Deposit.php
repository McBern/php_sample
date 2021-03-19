<?php

namespace Payservice;

use Payservice\CurrencyRate;
use Payservice\Utils;

class Deposit {
	private $amount;
	private $currency;
	private $chargePercentage;
	private $clientID;
	private $transactionDate;

	public function __construct($amount = 0, $currency = "EUR")
    {
    	$chargePercentage = new Rules();
        $this->amount = $amount;
        $this->currency = $currency;
        $this->chargePercentage = $chargePercentage->depositChargePercentage();
    }

	public function setAmount($amount = 0){
        $this->amount = $amount;
        return $this;
    }

	public function getAmount(){
        return $this->amount;
    }

	public function setCurrency($currency = "EUR"){
        $this->currency = $currency;
        return $this;
    }

	public function getCurrency(){
        return $this->currency;
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