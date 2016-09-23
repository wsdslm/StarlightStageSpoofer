import React, { Component } from 'react';
import { createStore } from 'redux';
import { connect } from 'react-redux';
import { mapDispatchToProps } from 'components/Home/Sidebar';

import template from './template.jsx';
//import ShikiCard from './mock/ShikiCard.json';

class SearchMenu extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cards: [],
            card: null
        };
    }

    render() {
        return template.apply(this);
    }
}

const mapStateToProps = (state) => ({
    layout: state.layout,
    user: state.login.user,
    search: state.pages.home.search
});

export default connect(mapStateToProps, mapDispatchToProps)(SearchMenu);

