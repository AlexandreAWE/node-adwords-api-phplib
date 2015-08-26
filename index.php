<?php

namespace NodeAdWordsApiPhpLib;

require_once 'lib/php/services/ReportDefinitionService.php';
require_once 'lib/php/services/ManagedCustomerService.php';

$method  = $argv[1];
$options = json_decode($argv[2], true);

switch ($method) {
	case 'ManagedCustomerService-createReporting': createReporting($options); break;
	case 'ManagedCustomerService-getAccountList' : getAccountList($options); break;
	default: throw new \Exception('Unknown Method'); break;
}

/**
 * Create Report
 * @param  [Array] $options
 */
function createReporting($options) {
	$ReportDefinitionService = new ReportDefinitionService();
	$ReportDefinitionService->auth($options['credentials']);
	$ReportDefinitionService->setClientCustomerId($options['clientCustomerId']);
	$ReportDefinitionService->createReport($options['reportDefinition']);

	$data = $ReportDefinitionService->getResult();
	$data = json_encode($data);

	fwrite(STDOUT, $data);
}

/**
 * List MCC Account
 * @param  [Array] $options
 */
function getAccountList($options) {
	$ManagedCustomerService = new ManagedCustomerService();
	$ManagedCustomerService->auth($options['credentials']);
	$ManagedCustomerService->setClientCustomerId($options['clientCustomerId']);

	$data = $ManagedCustomerService->getList();
	$data = json_encode($data);

	fwrite(STDOUT, $data);
}
