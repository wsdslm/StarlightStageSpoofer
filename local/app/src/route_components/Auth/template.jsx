import React from 'react';
import { push } from 'react-router-redux'; 
import { dispatchRoute } from 'routes';

import Paper from 'material-ui/Paper';
import FlatButton from 'material-ui/FlatButton';

import Styles from './styles';

export default class Template {
    constructor(form, input) {
        this.form = form;
        this.input = input;
    }

    render(state, props) {
        var form = this.form;
        var input = this.input;
        var dispatch = props.dispatch;
        var styles = Styles;

        styles = styles.toJS();
        return (
            <Paper style={styles.paper}>
                <h3 style={styles.header}>{form.title}</h3>
                <form onSubmit={form.submit.callback}>
                    {_.map(form.fields, (field, idx) => {
                        var parsed = field.split(":");
                        var type = parsed[0];
                        var args = parsed[1].split("|");
                        return input[type].apply(input, args);
                    })}
                    <div>
                        {this.renderErrorMessages(state)}
                    </div>
                    <div style={styles.buttonContainer}>
                        <FlatButton
                            type="submit"
                            primary={true}
                            label={form.submit.label} />
                        <FlatButton
                            type="button"
                            label={form.changePage.label}
                            onClick={() => dispatchRoute(dispatch, push, form.changePage.path)}/>
                    </div>
                </form>
            </Paper>
        );
    }

    renderErrorMessages(state) {
        if (!state.error) return null;
        return _.map(state.error, (messages, field) => {
            return _.map(messages, (message, idx) => (
                <div>{message}</div>
            ));
        });
    }
}

