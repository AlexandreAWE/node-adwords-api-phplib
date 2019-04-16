<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\mcm\CustomerService as GoogleCustomerService;

class CustomerService extends base {

    /**
	 * Return customer infos
	 * @return [Array]
	 */
	public function getInfos(){
		$adWordsServices = new AdWordsServices();
		$customerService = $adWordsServices->get($this->getSession(), GoogleCustomerService::class);
        $response = $customerService->getCustomers();

        return [
            'companyName' => $response[0]->getDescriptiveName(),
            'customerId'  => (string)$response[0]->getCustomerId(),
        ];
	}
}
