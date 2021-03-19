<?php

namespace Payservice;

class Rules {
	private $rules = array();

	public function __construct()
    {
        $this->rules['deposit_charge_percentage'] = 0.03;
        $this->rules['withdraw_commission_percentage_private'] = 0.03;
        $this->rules['withdraw_commission_percentage_business'] = 0.05;
    }

    public function depositChargePercentage(){
    	return $this->rules['deposit_charge_percentage'];
    }	

    public function withdrawPrivatePercentage(){
    	return $this->rules['withdraw_commission_percentage_private'];
    }	

    public function withdrawBusinessPercentage(){
    	return $this->rules['withdraw_commission_percentage_business'];
    }	


}