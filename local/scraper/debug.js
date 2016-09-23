const PORT = 3100;

var fs = require('fs');
var $path = require('path');
var express = require('express');
var bodyParser = require('body-parser');
var app = express();

app.use(bodyParser.json({ limit: '50mb' }));

app.get('/', function(req, res) {
	res.write("200 OK");
	res.end();
});

app.post('/', function(req, res) {
	if (req.body.json) {
		var path = Math.round(new Date().getTime() / 1000) + "." +
			req.body.path.split("/").join(".") + "." +
			req.body.method.toLowerCase() + "." +
			req.body.type + ".json";

		fs.appendFile($path.join(__dirname, "logs/" + path), req.body.json, function(err) {
			if (err) {
				res.write("503 Server Error");
			} else {
				res.write("200 OK");
			}

			res.end();
		});
	} else {
		console.log(
			"Path: " + req.body.path + "\n" +
			"Method: " + req.body.method + "\n"
		);
		res.write("200 OK");
		res.end();
	}
});

app.listen(PORT, function() {
	console.log("Listening at port " + PORT);
});
