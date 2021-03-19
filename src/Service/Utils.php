<?php

namespace Payservice;

abstract class Utils {
	public static function roundDecimalUp($amount, $precision=2){
		$pow = pow(10, $precision);
	    return (ceil($pow * $amount) + ceil($pow * $amount - ceil($pow * $amount))) / $pow;
	}

	public static function importCSVFileIntoArray($filename){
		$csv = array_map('str_getcsv', file($filename));
	    array_walk($csv, function(&$a) use ($csv) {
	      $a = array_combine($csv[0], $a);
	    });
	    array_shift($csv); 

	    return $csv;
	}

	public static function getCurrencyRate($rates = array(),$currency="EUR"){
		foreach($rates as $cur => $rate){
			if(strtoupper($cur)==strtoupper($currency)){
				return (float)$rate;
			}
		}

		return 1; //not found, return no rate conversion
	}

	public static function isThirdTransactionInWeek($transactions = array(),$currentTransaction){
		$transactionCount = 0;
		foreach($transactions as $transaction){
			$transWeekNo = date("W",strtotime($transaction->getTransactionDate()));
			$currentTransWeekNo = date("W",strtotime($currentTransaction->getTransactionDate()));

			if($transaction->getClientID()==$currentTransaction->getClientID() && 
				$transWeekNo==$currentTransWeekNo){
				$transactionCount++;
			}
		}

		return $transactionCount==3;
	}

}