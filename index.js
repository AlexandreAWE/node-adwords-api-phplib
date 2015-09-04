'use strict';

var child_process = require('child_process');
var async		  = require('async');

var _this = this;

/**
 * Concurrency request on AdWords API
 * @type {Number}
 */
exports.concurrency = 30;

/**
 * Queries queue
 * In order to access AdWords API PHP Lib we spawn child processes
 */
var queue = async.queue(function(options, callback){

	var stdout = '';
	var stderr = '';
	var child  = child_process.spawn('php', [__dirname+'/index.php', options.method, JSON.stringify(options)]);

		child.stdout.on('data', function(chunk){
			stdout += chunk.toString();
		});

		child.stderr.on('data', function(data){
			stderr += data.toString();
		});

		child.on('close', function(code){
			if (code === 0) {
				callback(null, stdout);
			}
			else{
				callback(stderr);
			}
		});

}, _this.concurrency);

/**
 * Add Task in queue
 * @param {Object}   options
 * @param {Function} callback
 */
var addTask = function addTask(options, callback) {
	queue.push(options, function(err, result){
		if (err) {
			callback(err);
		}
		else {
			try{
				result = result ? JSON.parse(result) : result;
				callback(null, result);
			}
			catch(err){
				console.log(result);
				callback(result+err.stack);
			}
		}
	});
};


/**
 * [ReportDefinitionService]
 * @type {Object}
 */
exports.ReportDefinitionService = {

	/**
	 * Create AdwordsReport
	 * @param  {Object} options
	 *   {Object} options.credentials
	 *   {String} options.credentials['client_id']
	 *   {String} options.credentials['client_secret']
	 *   {String} options.credentials['refresh_token']
	 *   {String} options.credentials['developer_token']
	 *
	 *   {Object} options.reportDefinition
	 *   {String} options.reportDefinition['reportType']
	 *   {Object} options.reportDefinition['periode']
	 *   {Date}   options.reportDefinition['periode']['start']
	 *   {Date}   options.reportDefinition['periode']['end']
	 *   {Array}  options.reportDefinition['fields']
	 *
	 * 	 {String} options.clientCustomerId
	 * @param  {Function} callback
	 * @return {Array}
	 */
	createReport: function createReport(options, callback) {
		options.method = 'ReportDefinitionService-createReporting';
		addTask(options, callback);
	}
};


/**
 * [getAccountList]
 * @type {Object}
 */
exports.ManagedCustomerService = {

	/**
	 * MCC Account List
	 * @param  {Object} options
	 *   {Object} options.credentials
	 *   {String} options.credentials['client_id']
	 *   {String} options.credentials['client_secret']
	 *   {String} options.credentials['refresh_token']
	 *   {String} options.credentials['developer_token']
	 *
	 * 	 {String} options.clientCustomerId
	 * @param  {Function} callback
	 * @return {Array}
	 */
	getAccountList: function getAccountList(options, callback) {
		options.method = 'ManagedCustomerService-getAccountList';
		addTask(options, callback);
	}
};

/**
 * [getCampaignList]
 * @type {Object}
 */
exports.CampaignService = {

	/**
	 * Account Campaign List
	 * @param  {Object} options
	 *   {Object} options.credentials
	 *   {String} options.credentials['client_id']
	 *   {String} options.credentials['client_secret']
	 *   {String} options.credentials['refresh_token']
	 *   {String} options.credentials['developer_token']
	 *
	 *   {Object} options.reportDefinition
	 *   {Array}  options.reportDefinition['fields']
	 *   {Array}  options.reportDefinition['predicates']
	 *
	 * 	 {String} options.clientCustomerId
	 * @param  {Function} callback
	 * @return {Array}
	 */
	getCampaignList: function getCampaignList(options, callback) {
		options.method = 'CampaignService-getCampaignList';
		addTask(options, callback);
	}
};
