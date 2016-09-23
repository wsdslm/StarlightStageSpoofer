import React from 'react';
import injectTapEventPlugin from 'react-tap-event-plugin';
injectTapEventPlugin();

import { Router, Route, IndexRoute } from 'react-router';
import { Provider } from 'react-redux';
import ReactDOM from 'react-dom';
import * as bootstrap from './bootstrap';

import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import { routes } from './routes';

import 'normalize-css';
import './index.css';

bootstrap.init();

ReactDOM.render((
    <Provider store={bootstrap.store}>
        <MuiThemeProvider muiTheme={getMuiTheme()}>
            <Router history={bootstrap.history}>
                {renderRoutes(routes)}
            </Router>
        </MuiThemeProvider>
    </Provider>
), document.getElementById('root'));

function renderRoutes(routes) {
    return _.map(routes, (obj, idx) => {
        const props = {
            path: obj.path,
            key: idx,
            component: obj.component
        };

        if (obj.routes) {
            return (
                <Route { ...props }>
                    {renderRoutes(obj.routes)}
                </Route>
            );
        } else {
            return <Route { ...props } />
        }
    });
}

