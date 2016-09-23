import { ActionType } from 'actions/entities';
import { GameCardSchema } from 'schemas';

export default (state={}, action) => {
    var keys = {
        gameCard: GameCardSchema.getIdAttribute()
    };
    switch (action.type) {
        case ActionType.ADD_GAME_CARDS:
            var ids = _.map(action.game_cards, card => card[keys.gameCard]);
            state.game_cards = _.concat(ids, state.game_cards);
            break;
        case ActionType.REMOVE_GAME_CARDS:
            state.game_cards = _.difference(state.game_cards, action.ids);
            break;
        case "REPLACE_GAME_CARDS":
            var ids = action.payload;
            var game_cards = [];
            state.game_cards = game_cards;
            break;
        case "TRAINING_UPDATE":
            console.log(state, action.payload.removed_card_ids);
            state.game_cards = _.difference(state.game_cards, action.payload.removed_card_ids);
            break;
        default:
            return state;
    }
    return _.assign({}, state);
}
