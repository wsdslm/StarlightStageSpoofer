import React, { Component } from 'react';
import { connect } from 'react-redux';
import template from './template.jsx';

class Titlebar extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open: true
        };
    }

    render() {
        return template.apply(this);
    }
}

const mapStateToProps = (state) => ({
    layout: state.layout
});

const mapDispatchToProps = (dispatch) => ({
    dispatch
});

export default connect(mapStateToProps, mapDispatchToProps)(Titlebar);
