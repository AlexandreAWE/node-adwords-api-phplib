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
	var child  = child_process.spawn('php', ['./index.php', options.method, JSON.stringify(options)]);

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
exports.createReport = function createReport(options, callback) {
	options.method = 'reporting';

	queue.push(options, function(err, result){
		if (err) {
			callback(err);
		}
		else {
			callback(null, JSON.parse(result));
		}
	});
};
