<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

class CampaignService extends base {

	/**
	 * Return account list of CustomerId
	 * @return [Array]
	 */
	public function getList(){

		$campaignService = $this->AdWordsUser->GetService('CampaignService', ADWORDS_VERSION);
		$selector = new \Selector();

		$selector->fields = array('Id', 'Name', 'Status');
		$selector->predicates = [new \Predicate('Status', 'EQUALS', 'ENABLED')];

		$campaigns = $campaignService->get($selector);
		$list = [];

		foreach ($campaigns->entries as $v) {
			$list[] = [
				'id'   => $v->id,
				'name' => $v->name
			];
		}

		return $list;
	}
}
