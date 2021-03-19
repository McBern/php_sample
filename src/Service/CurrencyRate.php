<?php

namespace Payservice;

class CurrencyRate {
	private static $currencyRateURL = 'https://api.exchangeratesapi.io/latest';

    //fetch latest currency rate from url
    public static function getLatestCurrencyRate(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$currencyRateURL);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch,CURLOPT_POST,false);
        $data = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $header_size);
        $body = substr($data, $header_size);

        curl_close($ch); 
        $rates = json_decode($body);

    	return $rates->rates;
    }	

}