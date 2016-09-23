import React from 'react';
import Paper from 'material-ui/Paper';

import Titlebar from 'components/Home/Titlebar';
import Sidebar from 'components/Home/Sidebar';
import SearchMenu from 'components/Home/SearchMenu';
import Card from 'components/Home/Card';

import Styles from './styles';
import Filter from './filter';

export default function() {
    var layout = this.props.layout;
    var styles = Styles;
    if (layout.mobile) {
        styles = styles.setIn(['base', 'paddingLeft'], "9px");
    }

    styles = styles.toJS();
    return (
        <div style={styles.base}>
            <Titlebar />
            <div style={styles.cardWrapper}>
                {renderCards.apply(this)}
            </div>
            <Sidebar />
            <SearchMenu />
        </div>
    );
}

function renderCards() {
    var gameUser = this.props.login.game_user;
    if (!gameUser) return "Loading...";
    var entities = this.props.entities;
    return filterCards(this.props, gameUser.game_cards, (gameCard) => <Card gameCard={entities.game_cards[gameCard]} key={gameCard} />);
}

function filterCards(props, gameCards, callback) {
    return _.filter(gameCards, filterCard(props)).map(callback);
}

function filterCard(props) {
    var filterText = props.filter;
    var entities = props.entities;

    var parsed = {};
    if (filterText) {
        _.each(filterText.split("|"), (token) => {
            var args = token.split(/[:,]/);
            var name = args[0];
            parsed[name] = args;
        });
    }

    var filter = Filter(entities.game_cards);
    return (card) => {
        card = entities.game_cards[card];
        var valid = true;
        _.each(parsed, (args, name) => {
            var invert = false;
            if (name[0] == '!') {
                name = name.substring(1);
                invert = true;
            }
            valid = filter(name, args, card);
            if (invert) valid = !valid;
            return valid;
        });
        return valid;
    };
}
