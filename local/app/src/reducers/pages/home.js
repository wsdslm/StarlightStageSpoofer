import { ActionType } from 'actions/pages/home';

export default function(state = {}, action) {
    switch (action.type) {
        case ActionType.SET_FILTER:
            state.filter = action.filter;
            break;
        case ActionType.SET_SEARCH:
            state.search = action.search;
            break;
        default:
            return state;
    }
    return { ...state };
}
