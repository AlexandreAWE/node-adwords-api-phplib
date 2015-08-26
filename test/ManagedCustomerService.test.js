'use strict';

var expect  = require('expect.js');
var adwords = require('../index');
var auth    = require('./auth.test.js');

describe('ManagedCustomerService - getAccountList', function(){

	it('Should return Array', function(done){

		this.timeout(100000000);

		var options = {
			credentials: auth,
			clientCustomerId: '462-328-8819'
		};

		adwords.ManagedCustomerService.getAccountList(options, function(err, result){
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
