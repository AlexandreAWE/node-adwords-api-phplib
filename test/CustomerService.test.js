'use strict';

var expect  = require('expect.js');
var adwords = require('../index');
var auth    = require('./auth.test.js');

describe('CustomerService - getInfos', function(){

	it('Should return companyName and CustomerId', function(done){

		this.timeout(100000000);

		var options = {
			credentials: auth
		};

		adwords.CustomerService.getInfos(options, function(err, result){
			if (err) {
				done(err);
			}
			else {
				console.log(require('util').inspect(result, { depth: null }));
				expect(result.companyName).to.be.ok();
				expect(result.companyName).to.be.a('string');
				expect(result.customerId).to.be.ok();
				expect(result.customerId).to.be.a('string');
				done();
			}
		});
	});
});
