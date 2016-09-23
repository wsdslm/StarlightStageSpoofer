import { Generator } from './index';

const generator = new Generator();

export const setMobileLayout = generator.generate('SET_MOBILE_LAYOUT', 'mobile');
export const toggleSidebar = generator.generate('TOGGLE_SIDEBAR');
export const setSidebar = generator.generate('SET_SIDEBAR', 'sidebar');
export const toggleSearchMenu = generator.generate('TOGGLE_SEARCH_MENU');
export const setSearchMenu = generator.generate('SET_SEARCH_MENU', 'search');
export const ActionType = generator.actionType();

