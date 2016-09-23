import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import template from './template.jsx';

export default class Card extends Component {
    constructor(props) {
        super(props);
        this.state = {
            gameCard: props.gameCard
        };
    }

    render() {
        return template.apply(this);
    }
}

