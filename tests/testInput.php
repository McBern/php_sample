<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';


use Payservice\CurrencyRate;
use Payservice\Deposit;
use Payservice\Withdraw;
use Payservice\Utils;
use Payservice\ClientType;


$rates = $a::getLatestCurrencyRate();
$transactions = array();

//load csv input file
$csv = Utils::importCSVFileIntoArray("input.csv");

foreach($csv as $line){
	if(strtolower($line['Transaction Type'])=="deposit"){
		$transaction = new Deposit();
		$transaction->setAmount((float)$line['Amount'])
			->setClientID($line['Client ID'])
			->setTransactionDate($line['Date'])
			->setCurrency(strtoupper($line['Currency']));

		if($transaction->getCurrency()<>"EUR"){
			$convertedAmount = Utils::getCurrencyRate($rates, $transaction->getCurrency()) * $transaction->getAmount();
			$transaction->setAmount((float) $convertedAmount);
		}

		echo $transaction->calculateCommission()."<br/>";
	}

	if(strtolower($line['Transaction Type'])=="withdraw"){
		$transaction = new Withdraw();
		$transaction->setAmount((float)$line['Amount'])
			->setClientID($line['Client ID'])
			->setTransactionDate($line['Date'])
			->setCurrency(strtoupper($line['Currency']));

		if($transaction->getCurrency()<>"EUR"){
			$convertedAmount = Utils::getCurrencyRate($rates, $transaction->getCurrency()) * $transaction->getAmount();
			$transaction->setAmount((float) $convertedAmount);
		}

		if(strtolower($line['Client Type'])=="private"){
			$transaction->setClientType(ClientType::PrivateClient);
		}
		if(strtolower($line['Client Type'])=="business"){
			$transaction->setClientType(ClientType::BusinessClient);
		}

		//======================================================================
		//check for private withdraw for 3 transaction per week
		//make the 3rd transaction free of charge if the amount == 1000 EUR
		//if amount is > 1000 EUR only charge commission after deducting 1000 from the amount
		//if the currency is not in EUR the convert it using the latest conversion rate from the website
		//======================================================================
		if($transaction->getClientType()==ClientType::PrivateClient){
			$transactions[] = $transaction;

			if(Utils::isThirdTransactionInWeek($transactions,$transaction)){
				if($transaction->getAmount()>=1000){
					$transaction->setAmount($transaction->getAmount()-1000); //will apply the 0.03% fee for the exceeded amount if there is
				}
				echo $transaction->calculateCommission()."<br/>";
			}else{
				echo $transaction->calculateCommission()."<br/>";
			}

		}else{
			echo $transaction->calculateCommission()."<br/>";
		}
	}

}
