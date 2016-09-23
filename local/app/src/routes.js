import { BASE_PATH } from 'globals';
import App from './components/App';
import { Login, Register } from './route_components/Auth';
import Home from './route_components/Home';

const generated = {};

export function generatePath(path) {
    var key = path;
    if (path == "/") path = "";
    path = BASE_PATH + "/app" + path;
    generated[key] = path;
    return path;
}

export function dispatchRoute(dispatch, action, path) {
    return dispatch(action(generated[path]));
}

export const routes = [
    {
        path: generatePath("/"),
        component: App,
        routes: [
            {
                path: generatePath("/home"),
                component: Home
            },
            {
                path: generatePath("/login"),
                component: Login
            },
            {
                path: generatePath("/register"),
                component: Register
            }
        ]
    }
];

