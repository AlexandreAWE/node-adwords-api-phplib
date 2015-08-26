<?php

require_once 'lib/php/methods/reporting.php';

$method  = $argv[1];
$options = json_decode($argv[2], true);

switch ($method) {
	case 'reporting': createReporting($options); break;
	default: throw new Exception('Unknown Method'); break;
}

function createReporting($options) {
	$reporting = new AdWordsReporting();
	$reporting->auth($options['credentials']);
	$reporting->setClientCustomerId($options['clientCustomerId']);
	$reporting->createReport($options['reportDefinition']);

	$data = $reporting->getResult();
	$data = json_encode($data);

	fwrite(STDOUT, $data);
}
