<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';
require_once ADWORDS_UTIL_PATH.'/ReportUtils.php';

class ReportDefinitionService extends base {

	private $tempDirPath = '/../../../temp/';
	private $result;

	/**
	 * @param [Array]  $definition
	 *   $definition['reportType']
 	 *   $definition['periode']
 	 *   $definition['fields']
 	 *   $definition['predicates']
	 */
	public function createReport(Array $definition) {
		$filePath = dirname(__FILE__).$this->tempDirPath.uniqid().'report.csv';
		$this->AdWordsUser->LoadService('ReportDefinitionService', ADWORDS_VERSION);

		// Create selector.
		$selector = new \Selector();
		$selector->fields 	  = $definition['fields'];
		$selector->dateRange  = new \DateRange(
			(new \DateTime($definition['periode']['start']))->format('Ymd'),
			(new \DateTime($definition['periode']['end']))->format('Ymd')
		);

		// predicates
		if (isset($definition['predicates']) && count($definition['predicates']) > 0){
			foreach ($definition['predicates'] as $predicate) {
				$selector->predicates[] = new \Predicate($predicate['field'], $predicate['condition'], $predicate['value']);
			}
		}

		// Create report definition.
		$reportDefinition = new \ReportDefinition();
		$reportDefinition->selector = $selector;
		$reportDefinition->reportName = 'Custom Report';
		$reportDefinition->dateRangeType = 'CUSTOM_DATE';
		$reportDefinition->reportType = $definition['reportType'];
		$reportDefinition->downloadFormat = 'CSV';

		$options = array('version' => ADWORDS_VERSION, 'includeZeroImpressions' => false);

		// Download report.
		try{
            $reportUtils = new \ReportUtils();
			$report 	  = $reportUtils->DownloadReport($reportDefinition, $filePath, $this->AdWordsUser, $options);
			$this->result = $this->parseAccountReport($filePath);
			$this->result = $this->convertMicroMoney($definition['fields'], $this->result);
		}
		catch (\Exception $e) {
		  	printf("An error has occurred: %s\n", $e->getMessage());
		}

		unlink($filePath);
	}

	/**
	 * @return [Array]
	 */
	public function getResult() {
		return $this->result;
	}


	/**
	 * @param (String) $filePath
	 * @return (Array) $result
	 **/
	private function parseAccountReport($filePath) {
		$i=0;
		if (($handle = fopen($filePath, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		    	if($i >= 2){ //On saute les 2 première lignes qui sont "nom du rapport" et nom des champs

		    		//Si le compte à aucune datas, on corrige le bug du rapport
		    		for($i=0;$i<count($data);$i++){
		    			if($data[$i] == "Total") $data[$i] = 0;
		    		}
		    		$result[] = $data;

		    	}
		    	$i++;
		    }
		    fclose($handle);
		}
		if(count($result) > 1) array_pop($result); // On supprime la ligne Total

		return $result;
	}

	/**
	* @param (Array) $fields
	* @param (Array) $data
	**/
	private function convertMicroMoney($fields, $data){

		$moneyKPI = $this->getMoneyFields();

		foreach ($fields as $index => $keyName) {
			foreach ($data as $i => $item) {
				if (in_array($keyName, $moneyKPI)) {
					$data[$i][$index] = $data[$i][$index] > 0 ? round($data[$i][$index] / 1000000, 2) : 0;
					$data[$i][$index] = strval($data[$i][$index]);
				}
			}
		}

		return $data;
	}

	/**
	 * Money fields list
	 **/
	private function getMoneyFields(){
		return array(
			'Cost',
			'CostPerConversion',
			'CostPerConvertedClick',
			'CostPerConversionManyPerClick',
			'AverageCpc',
			'AverageCpm',
			'AvgCostForOfflineInteraction',
			'OfflineInteractionCost',
			'TotalCost',
		);
	}

}
