import * as actions from 'actions/layout';
import { setFilter } from 'actions/pages/home';
import { putObject } from 'helpers/ajax';

export function setSidebar(dispatch) {
    return (open) => {
        dispatch(actions.setSidebar(open));
    };
}

export function updateSettings(user) {
    return _.debounce((settings) => {
        putObject('GameUser', user.viewer_id, {
            settings_json: JSON.stringify(settings)
        });
    }, 300);
}

export function trackSearch(dispatch) {
    return _.debounce((evt, val) => {
        dispatch(setFilter(val));
    }, 300);
}
