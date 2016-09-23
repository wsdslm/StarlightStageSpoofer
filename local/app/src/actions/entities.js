import { Generator } from './index';

const generator = new Generator();

export const addGameCards = generator.generate("ADD_GAME_CARDS", "game_cards");
export const removeGameCards = generator.generate("REMOVE_GAME_CARDS", "ids");
export const ActionType = generator.actionType();
