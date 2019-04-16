<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\CampaignService as GoogleCampaignService;

class CampaignService extends base {

	/**
	 * Return account list of CustomerId
	 * @return [Array]
	 */
	public function getList(){

		$adWordsServices = new AdWordsServices();
		$campaignService = $adWordsServices->get($this->getSession(), GoogleCampaignService::class);

		$selector = new Selector();

		$selector->setFields(array('Id', 'Name', 'Status'));
		$selector->setPredicates([new Predicate('Status', 'EQUALS', ['ENABLED'])]);

		$campaigns = $campaignService->get($selector);
		$list = [];

		foreach ($campaigns->getEntries() as $v) {
			$list[] = [
				'id'   => $v->getId(),
				'name' => $v->getName()
			];
		}

		return $list;
	}
}
