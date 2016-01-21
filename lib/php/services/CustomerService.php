<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

class CustomerService extends base {

    /**
	 * Return customer infos
	 * @return [Array]
	 */
	public function getInfos(){
		$CustomerService = $this->AdWordsUser->GetService('CustomerService', ADWORDS_VERSION);
        $response = $CustomerService->get();

        return [
            'companyName' => $response->companyName,
            'customerId'  => $response->customerId,
        ];
	}
}
