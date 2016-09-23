var _ = require('lodash');
var cheerio = require('./node_modules/cheerio/lib/cheerio');
var FetchQueue = require('./src/FetchQueue');
var DatabaseQueue = require('./src/DatabaseQueue');

var fetchQueue = new FetchQueue();
var databaseQueue = new DatabaseQueue({
	host: "localhost",
	user: "spoofer",
	password: "spoofer",
	database: "spoofer"
});

databaseQueue.connect(function(err) {
	if (err) throw err;

	fetchQueue.queue({
		url: "https://starlight.kirara.ca/history",
		success: function(resp) {
			parseHTML(resp.data);
		}
	});
});

var maxQueue = 0;
var currentQueue = 0;
function parseHTML(html) {
	var $ = cheerio.load(html);

	var query = $(".iconex_row > a.noline");

	maxQueue = query.length;
	query.each(function(idx, el) {
		var cardIds = [];
		var href = $(el).attr("href").trim();
		var cardId = Number(href.match(/^\/char\/\d+#c_(\d+)_head$/)[1]);

		cardIds.push(cardId);
		cardIds.push(cardId+1);

		fetchCards(cardIds);
	});
}

function fetchCards(cardIds) {
	if (!Array.isArray(cardIds)) cardIds = [ cardIds ];
	fetchQueue.queue({
		url: "https://starlight.kirara.ca/api/v1/card_t/" + cardIds.join(","),
		success: function(resp) {
			insertCardToDB(resp.data.result);

			currentQueue++;
			if (currentQueue >= maxQueue) {
				console.log("Executing!!!!");
				databaseQueue.execute();
			} else {
				console.log("Queueing " + currentQueue + "/" + maxQueue);
			}
		}
	});
}

function insertCardToDB(cards) {
	if (cards.length <= 0) return;

	var card = cards.shift();
	var chara = card.chara;
	var rarity = card.rarity;

	delete card.chara;
	delete card.rarity;

	databaseQueue.queue("characters", {
		id: chara.chara_id,
		name: chara.conventional,
		type: chara.type,
		chara_json: JSON.stringify(chara)
	});

	databaseQueue.queue("cards", {
		id: card.id,
		chara_id: card.chara_id,
		rarity: rarity.rarity,
		max_level: rarity.base_max_level,
		max_love: rarity.max_love,
		image: card.card_image_ref,
		card_json: JSON.stringify(card),
		rarity_json: JSON.stringify(rarity)
	});

	insertCardToDB(cards);
}
