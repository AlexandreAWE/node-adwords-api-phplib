<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

class ManagedCustomerService extends base {

	public function getList() {
		$list = $this->getAccounts();

		foreach ($list as $index => $account) {
			$this->AdWordsUser->SetClientCustomerId($account['customerId']);
			$list[$index]['subAccounts'] = $this->getAccounts();
		}

		return $list;
	}

	/**
	 * Return account list of CustomerId
	 * @return [Array]
	 */
	private function getAccounts(){
		$managedCustomerService = $this->AdWordsUser->GetService('ManagedCustomerService', ADWORDS_VERSION);
		$selector = new \Selector();
		$selector->fields = array('Name','CustomerId');
		$graph = $managedCustomerService->get($selector);
		$accounts = array();

		foreach ($graph->entries as $account){
		  $accounts[] = [
			  "customerId" => $account->customerId,
			  "name" 	   => $account->name
		  ];
		}

		return $accounts;
	}
}
