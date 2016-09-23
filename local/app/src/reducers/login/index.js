import { combineReducers } from 'redux';
import game_user from './game_user';
import user from './user';

export default combineReducers({
    user, game_user
});
