import { ActionType } from 'actions/layout';

export default (state={}, action) => {
    switch (action.type) {
        case ActionType.SET_MOBILE_LAYOUT:
            state.mobile = action.mobile;
            break;
        case ActionType.TOGGLE_SIDEBAR:
            state.sidebar = !state.sidebar;
            break;
        case ActionType.SET_SIDEBAR:
            state.sidebar = action.sidebar;
            break;
        case ActionType.TOGGLE_SEARCH_MENU:
            state.search = !state.search;
            break;
        case ActionType.SET_SEARCH_MENU:
            state.search = action.search;
            break;
        default:
            return state;
    }
    return _.assign({}, state);
};
