export default function(gameCards) {
    return function(name, args, card) {
        return (filters[name] || filters.default)(args, card, gameCards);
    }
};

var filters = {
    unit(args, card) {
        var valid = false;
        var unitIds = args.slice(1);
        _.each(card.game_units, (unit) => {
            valid = _.includes(unitIds, String(unit.unit_id));
            return !valid;
        });
        return valid;
    },
    modified(args, card) {
        return _.isObject(card.modified_json);
    },
    unique(args, card, gameCards) {
        var count = 0;
        _.each(gameCards, (gameCard) => {
            if (gameCard.card_id == card.card_id) count++;
        });
        return count < 2;
    },
    default(args, card) {
        var name = args[0]
        var searched = args.slice(1);

        var original = card.card_json;
        var modified = card.modified_json || {};
        return _.includes(searched, String(modified[name] || original[name]));
    }
}
