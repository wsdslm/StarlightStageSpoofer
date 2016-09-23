import { Generator } from 'actions';

const generator = new Generator();

export const setFilter = generator.generate('SET_FILTER', 'filter');
export const setSearch = generator.generate('SET_SEARCH', 'search');
export const ActionType = generator.actionType();
