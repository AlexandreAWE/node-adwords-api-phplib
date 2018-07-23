<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201806\cm\Selector;
use Google\AdsApi\AdWords\v201806\mcm\ManagedCustomerService as GoogleManagedCustomerService;

class ManagedCustomerService extends base {

	public function getList() {
		$list = $this->getAccounts();

		foreach ($list as $index => $account) {
			$this->session->withClientCustomerId($account['customerId']);
			$list[$index]['subAccounts'] = $this->getAccounts();
		}

		return $list;
	}

	/**
	 * Return account list of CustomerId
	 * @return [Array]
	 */
	private function getAccounts(){
		$adWordsServices = new AdWordsServices();
		$managedCustomerService = $adWordsServices->get($this->getSession(), GoogleManagedCustomerService::class);

		$selector = new Selector();
		$selector->setFields(array('Name','CustomerId'));
		$graph = $managedCustomerService->get($selector);
		$accounts = array();

		foreach ($graph->getEntries() as $account){
		  if ($this->session->GetClientCustomerId() !== $account->getCustomerId()) {
			  $accounts[] = [
				  "customerId" => $account->getCustomerId(),
				  "name" 	   => $account->getName()
			  ];
		  }
		}

		return $accounts;
	}
}
