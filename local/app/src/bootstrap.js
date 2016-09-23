import { createStore, combineReducers, applyMiddleware } from 'redux';
import { syncHistoryWithStore, routerReducer, routerMiddleware, replace } from 'react-router-redux';
import { browserHistory } from 'react-router';
import { normalize } from 'normalizr';

import { setMobileLayout } from 'actions/layout';
import { updateUser } from 'actions/login';

import { isMobile } from 'helpers';
import { fetchObject } from 'helpers/ajax';
import { dispatchRoute } from 'routes';
import reducers from 'reducers';
import { UserSchema } from 'schemas';

const entities = window.user ? getEntities(window.user).entities : null;

var user = window.user ? entities.users[window.user.id] : null;
var game_user = user ? entities.game_users[user.game_user] : null;

const initialState = {
    entities,
    login: {
        user, game_user
    },
    layout: {
        sidebar: !isMobile(),
        mobile: isMobile(),
        search: false
    },
    pages: {
        home: {
            filter: null,
            search: null
        }
    }
};

const middleware = routerMiddleware(browserHistory);
export const store = createStore(
    combineReducers(_.assign(
        reducers,
        { routing: routerReducer }
    )),
    initialState,
    applyMiddleware(middleware)
);

export const history = syncHistoryWithStore(browserHistory, store);

export function init() {
    var state = store.getState();
    if (state.login.user) {
        dispatchRoute(store.dispatch, replace, '/home');
    } else {
        dispatchRoute(store.dispatch, replace, '/login');
    }

    $(window).resize(() => {
        var state = store.getState();
        if (state.layout.mobile && !isMobile()) {
            store.dispatch(setMobileLayout(false));
        } else if (!state.layout.mobile && isMobile()) {
            store.dispatch(setMobileLayout(true));
        }
    });
}

function fetchUserData() {
    var state = store.getState();
    var id = state.login.id;
    var _with = [
        "gameUser.gameCards.card.character",
        "gameUser.gameCards.gameUnits"
    ];

    fetchObject('User', {
        success: (User) => {
            store.dispatch(updateUser(User));
        },
        "with": _with, id
    });
}

function getEntities(user) {
    return normalize(user, UserSchema);
}
