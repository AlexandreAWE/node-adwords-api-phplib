<?php

namespace NodeAdWordsApiPhpLib;

require_once dirname(__FILE__).'/../base.php';

use Google\AdsApi\AdWords\Reporting\v201702\ReportDefinition;
use Google\AdsApi\AdWords\v201702\cm\ReportDefinitionReportType;
use Google\AdsApi\AdWords\v201702\cm\Selector;
use Google\AdsApi\AdWords\v201702\cm\DateRange;
use Google\AdsApi\AdWords\v201702\cm\Predicate;
use Google\AdsApi\AdWords\Reporting\v201702\ReportDownloader;

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

		// Create Data range
		$dateRange = new DateRange();
        $dateRange->setMin((new \DateTime($definition['periode']['start']))->format('Ymd'));
        $dateRange->setMax((new \DateTime($definition['periode']['end']))->format('Ymd'));

		// Set predicates
		$predicates = [];
		if (isset($definition['predicates']) && count($definition['predicates']) > 0){
			foreach ($definition['predicates'] as $predicate) {
				$predicates[] = new Predicate($predicate['field'], $predicate['condition'], $predicate['value']);
			}
		}

		// Create selector.
		$selector = new Selector();
		$selector->setFields($definition['fields']);
        $selector->setDateRange($dateRange);
		$selector->setPredicates($predicates);


		// Create report definition.
		$reportDefinition = new ReportDefinition();
		$reportDefinition->setSelector($selector);
		$reportDefinition->setReportName('Custom Report');
		$reportDefinition->setDateRangeType('CUSTOM_DATE');
		$reportDefinition->setReportType($definition['reportType']);
		$reportDefinition->setDownloadFormat('CSV');

		// Download report.
		try{
			$reportDownloader = new ReportDownloader($this->getSession());
			$report = $reportDownloader->downloadReport($reportDefinition);
			$report->saveToFile($filePath);
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
