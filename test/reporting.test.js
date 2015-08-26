'use strict';

var async	= require('async');
var expect  = require('expect.js');
var adwords = require('../index');
var auth    = require('./auth.test.js');

describe('Method - Reporting', function(){

	it('Should return Array', function(done){
		this.timeout(30000);

		var options = {
			'credentials': auth,
			'reportDefinition' : {
				'reportType'   : 'ACCOUNT_PERFORMANCE_REPORT',
				'periode' 	   : {'start': new Date('2015-01-01'), 'end': new Date('2015-01-31')},
				'fields' 	   : ['Clicks', 'ConversionValue', 'Ctr', 'Cost']
			},
			'clientCustomerId' : '734-817-8239'
		};

		adwords.createReport(options, function(err, result){
			if (err) {
				done(err);
			}
			else {
				expect(result).to.be.an('array');
				done();
			}
		});
	});
});
