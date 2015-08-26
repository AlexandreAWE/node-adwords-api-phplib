<?php

require_once dirname(__FILE__).'/init.php';

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
		$OAuth2 = array(
			'client_id' => $options['client_id'],
			'client_secret' => $options['client_secret'],
			'refresh_token' => $options['refresh_token']
		);

		$this->AdWordsUser = new AdWordsUser(NULL, $options['developer_token'], NULL, NULL, NULL, $OAuth2);
	}
}
