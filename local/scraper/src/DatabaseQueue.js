var mysql = require('mysql');

module.exports = function(config) {
	var con = mysql.createConnection(config);
	var _queue = [];
	var self = this;

	self.connect = con.connect.bind(con);

	self.queue = function(table, values, callback) {
		_queue.push({
			table, values, callback
		});
	};

	self.execute = function() {
		if (_queue.length <= 0) {
			con.end();
			return;
		}

		var opt = _queue.shift();
		insertOrUpdate(opt.table, opt.values, function() {
			opt.callback && opt.callback();
			self.execute();
		});
	};

	function insertOrUpdate(table, values, callback) {
		con.query("SELECT * FROM " + table + " WHERE id = ?", [values.id], function(err, rows) {
			if (err) throw err;

			if (rows.length > 0) {
				updateDB(table, values, callback);
			} else {
				insertDB(table, values, callback);
			}
		});
	}

	function insertDB(table, values, callback) {
		con.query("INSERT INTO " + table + " SET ?", [values], function(err) {
			if (err) throw err;
			callback();
		});
	}

	function updateDB(table, values, callback) {
		callback();
		/*
		con.query("UPDATE " + table + " SET ? WHERE id = ", [values, values.id], function(err) {
			if (err) throw err;
		});
		*/
	}
};