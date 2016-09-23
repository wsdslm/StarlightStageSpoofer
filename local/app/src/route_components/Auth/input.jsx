import React from 'react';
import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';

import { traverseObject } from 'helpers';
import Ajax from './ajax';
import Styles from './styles';

const styles = Styles.toJS();

export default class Input {
    constructor() {
        this._data = {};
        this.styles = Styles.toJS();
    }

    textField(key, hint, type="text", props={}) {
        return (
            <TextField 
                key={key}
                name={key}
                hintText={hint} 
                style={this.styles.textField}
                type={type}
                onChange={this.databind(key)}
                { ...props } />
        );
    }

    checkbox(key, label, props={}) {
        return (
            <Checkbox
                style={this.styles.checkbox}
                key={key}
                name={key}
                label={label}
                onCheck={this.databind(key)} 
                { ...props } />
        );
    }
    
    data(key, value) {
        return key !== undefined 
            ? traverseObject(this._data, key, value) 
            : this._data;
    }

    databind(key) {
        var self = this;
        return (evt, val) => {
            val = val || evt.target.value;
            self.data(key, val);
        };
    };
}

