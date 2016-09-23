var axios = require('axios');

const MAX_CONCURRENCY = 4;

module.exports = function() {
	var _queue = [];
	var _executing = [];
	var self = this;

	self.queue = function(options) {
		if (_executing.length < MAX_CONCURRENCY) {
			_executing.push(options);
			self.executeFetch();
		} else {
			_queue.push(options);
		}
	};

	self.nextQueue = function() {
		if (_queue.length > 0) {
			_executing.push(_queue.shift());
			self.executeFetch();
		}
	};
	
	self.executeFetch = function() {
		var opt = _executing[0];
		axios.get(opt.url)
			.then(function(resp) {
				_executing.shift();
				opt.success(resp);
				self.nextQueue();
			})
			.catch(console.error);
	};
}