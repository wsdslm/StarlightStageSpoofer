import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router';
import { addGameCards, removeGameCards } from 'actions/login';

import template from './template.jsx';
import realtime from './realtime';

class Home extends Component {
    componentDidMount() {
        realtime(this.props);
    }

    render() {
        return template.apply(this);
    }
}

const mapStateToProps = (state) => ({
    entities: state.entities,
    login: state.login,
    layout: state.layout,
    filter: state.pages.home.filter
});

const mapDispatchToProps = (dispatch) => ({
    dispatch,
    addGameCards(gameCards) {
        dispatch(addGameCards(gameCards));
    },
    removeGameCards(ids) {
        dispatch(removeGameCards(ids));
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(Home);
