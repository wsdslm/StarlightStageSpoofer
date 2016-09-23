import React, { Component } from 'react';
import { connect } from 'react-redux';
import { mapDispatchToProps } from 'helpers/redux';
import template from './template.jsx';

class Sidebar extends Component {
    render() {
        return template.apply(this);
    }
}

const mapStateToProps = (state) => ({
    layout: state.layout,
    login: state.login,
    filter: state.pages.home.filter
});

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar);
