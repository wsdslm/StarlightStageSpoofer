import { ActionType } from 'actions/entities';
import { GameCardSchema } from 'schemas';

export default (state={}, action) => {
    var keys = {
        gameCard: GameCardSchema.getIdAttribute()
    };
    switch (action.type) {
        case ActionType.ADD_GAME_CARDS:
            _.each(action.game_cards, (card) => {
                state.game_cards[keys.gameCard] = card;
            });
            break;
        case ActionType.REMOVE_GAME_CARDS:
            _.each(action.ids, (id) => {
                delete state.game_cards[id];
            });
            break;
        case "REPLACE_GAME_CARDS":
            var ids = action.payload;
            var card = state.game_cards[ids.old];
            card[keys.gameCard] = ids.new;
            state.game_cards[ids.new] = card;
            delete state.game_cards[ids.old];
            break;
        case "TRAINING_UPDATE":
            _.each(action.payload.removed_card_ids, (id) => {
                delete state.game_cards[id];
            });
            var updated = action.payload.updated_card;
            var card = state.game_cards[updated[keys.gameCard]];
            card.card_json = JSON.parse(updated.card_json);
            break;
        default:
            return state;
    }
    return _.assign({}, state);
}
