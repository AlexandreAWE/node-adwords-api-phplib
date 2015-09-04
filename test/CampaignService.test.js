'use strict';

var expect  = require('expect.js');
var adwords = require('../index');
var auth    = require('./auth.test.js');

describe('CampaignService - getList', function(){

	it('Should return Array', function(done){
		this.timeout(30000);

		var options = {
			'credentials': auth,
			'clientCustomerId' : '928-109-2660'
		};

		adwords.CampaignService.getCampaignList(options, function(err, result){
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
