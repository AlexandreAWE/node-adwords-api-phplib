'use strict';

var async	= require('async');
var expect  = require('expect.js');
var adwords = require('../index');
var options = require('./options.test.js');

describe('Method - Reporting', function(){

	it('Should return Array', function(done){
		this.timeout(30000);
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
