<?php

require_once 'lib/php/methods/reporting.php';
require_once 'lib/php/methods/account.php';

$method  = $argv[1];
$options = json_decode($argv[2], true);

switch ($method) {
	case 'reporting'   : createReporting($options); break;
	case 'accountList' : accountList($options); break;
	default: throw new Exception('Unknown Method'); break;
}

/**
 * Create Report
 * @param  [Array] $options
 */
function createReporting($options) {
	$reporting = new AdWordsReporting();
	$reporting->auth($options['credentials']);
	$reporting->setClientCustomerId($options['clientCustomerId']);
	$reporting->createReport($options['reportDefinition']);

	$data = $reporting->getResult();
	$data = json_encode($data);

	fwrite(STDOUT, $data);
}

/**
 * List MCC Account
 * @param  [Array] $options
 */
function accountList($options) {
	$account = new Account();
	$account->auth($options['credentials']);
	$account->setClientCustomerId($options['clientCustomerId']);

	$data = $account->getList();
	$data = json_encode($data);

	fwrite(STDOUT, $data);
}
