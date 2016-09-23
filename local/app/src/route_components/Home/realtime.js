import PubNub from 'pubnub';
import { BASE_API_URL } from 'globals';
import { ActionType } from 'actions/login';

export default function realtime(props) {
    var pubnub = new PubNub(window.pubnub_options);
    pubnub.addListener({
        message(message) {
            var json = JSON.parse(message.message);
            pushEvents(props, json.type, json.data);
        }
    });
    pubnub.subscribe({
        channels: ["channel-user-" + props.login.user.id]
    });
}

function pushEvents(props, type, payload) {
    switch (type) {
        case ActionType.ADD_GAME_CARDS:
            searchGameCards(payload, (gameCards) => {
                props.addGameCards(gameCards);
            });
            break;
        case ActionType.REMOVE_GAME_CARDS:
            props.removeGameCards(payload);
            break;
        default:
            props.dispatch({ type, payload });
            break;
    }
}

function searchGameCards(serialIds, success) {
    var url = BASE_API_URL + "/user_search/game_cards?q=" + (_.isArray(serialIds) ? serialIds.join(",") : serialIds.toString());
    $.ajax({
        url,
        type: "GET",
        dataType: "json",
        success,
        error: console.error.bind(console)
    });
}
