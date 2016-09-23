import { toggleSidebar, toggleSearchMenu } from 'actions/layout';

export function sidebar(dispatch) {
    return () => {
        dispatch(toggleSidebar());
    };
}

export function search(dispatch) {
    return () => {
        dispatch(toggleSearchMenu());
    };
}
