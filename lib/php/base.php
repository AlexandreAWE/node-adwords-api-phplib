<?php
namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../../vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\ReportSettingsBuilder;

class base {

	protected $AdWordsUser;

	/**
	 * @param [Array] $Options
	 *   $options['client_id']
	 *   $options['client_secret']
	 *   $options['refresh_token']
	 *   $options['developer_token']
	 */
	public function auth(Array $options)
	{
		$oAuth2Credential = (new OAuth2TokenBuilder())
			->withClientId($options['client_id'])
			->withClientSecret($options['client_secret'])
			->withRefreshToken($options['refresh_token'])
			->build();

        $reportSettings = (new ReportSettingsBuilder())
            ->includeZeroImpressions(false)
            ->build();

		$this->session = (new AdWordsSessionBuilder())
            ->withOAuth2Credential($oAuth2Credential)
            ->withDeveloperToken($options['developer_token'])
			->withReportSettings($reportSettings);
	}

	/**
	 * @param [String] $id
	 */
	public function setClientCustomerId($id) {
		$this->session->withClientCustomerId($id);
	}

	public function getSession() {
		return $this->session->build();
	}
}
