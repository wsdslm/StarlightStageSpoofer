import { Component } from 'react';
import { connect } from 'react-redux';
import { mapDispatchToProps } from 'helpers/redux';

import Events from './events';
import Input from './input.jsx';
import Template from './template.jsx';

export default class AuthComponent extends Component {
    constructor(props) {
        super(props);
        var input = new Input();
        var events = new Events(input, this.handleError.bind(this));
        var form = this.getForm(events);

        this.template = new Template(form, input);
        this.state = {
            error: null
        };
    }

    handleError(err) {
        this.setState({
            error: err.responseJSON
        });
    }

    render() {
        return this.template.render(this.state, this.props);
    }
}

