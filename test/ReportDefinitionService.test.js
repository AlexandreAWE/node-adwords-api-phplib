'use strict';

var async	= require('async');
var expect  = require('expect.js');
var adwords = require('../index');
var auth    = require('./auth.test.js');

describe('ReportDefinitionService - createReporting', function(){

	it('Should return Array', function(done){
		this.timeout(30000);

		var options = {
			'credentials': auth,
			'reportDefinition' : {
				'reportType'   : 'KEYWORDS_PERFORMANCE_REPORT',
				'periode' 	   : {'start': new Date('2017-10-01'), 'end': new Date('2018-03-31')},
				'fields' 	   : ['Clicks', 'ConversionValue', 'Ctr', 'Cost'],
				'predicates'   :[
                    { value: [ 'ENABLED' ], condition: 'EQUALS', field: 'Status' }
				]
			},
            'numberResults': 10,
			'clientCustomerId' : '4363437586'
		};

		adwords.ReportDefinitionService.createReport(options, function(err, result){
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
