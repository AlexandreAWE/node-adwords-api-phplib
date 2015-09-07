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
				'reportType'   : 'CAMPAIGN_PERFORMANCE_REPORT',
				'periode' 	   : {'start': new Date('2015-01-01'), 'end': new Date('2015-01-31')},
				'fields' 	   : ['Clicks', 'ConversionValue', 'Ctr', 'Cost'],
				'predicates'   :[
					{
                        "value" : ["59745991", "59746111"],
                        "condition" : "IN",
                        "field" : "CampaignId"
                    }
				]
			},
			'clientCustomerId' : '7025334984'
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
