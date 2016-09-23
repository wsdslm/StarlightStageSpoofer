import React from 'react';

import Paper from 'material-ui/Paper';
import Drawer from 'material-ui/Drawer';
import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';
import FlatButton from 'material-ui/FlatButton';
import { List, ListItem } from 'material-ui/List';
import Dialog from 'material-ui/Dialog';
import Avatar from 'material-ui/Avatar';

import Styles from './styles';
import Info from './info';
import { closeOverlay, trackSearch, backToSearch } from './events';
import { setSearchMenu } from 'actions/layout';
import { parseRarity } from 'helpers/card';
import { traverseObject } from 'helpers';

export default function() {
    var layout = this.props.layout;
    var dispatch = this.props.dispatch;
    var dialog = {
        content: null,
        actions: []
    };

    var styles = this.styles = Styles.toJS();
    if (this.state.card) {
        renderSelectedCard.apply(this, [dialog]);
    } else if (this.state.cards) {
        renderSearchMenu.apply(this, [dialog]);
    }

    return (
        <Dialog
            title={dialog.title}
            open={layout.search}
            actions={dialog.actions}
            modal={false}
            contentStyle={styles.dialog}
            onRequestClose={() => dispatch(setSearchMenu(false))}
            autoScrollBodyContent={true}>
            {dialog.content}
        </Dialog>
    );
}

function renderSelectedCard(dialog) {
    var card = this.state.card;
    var json = card.card_json;
    var styles = Styles.get('card');

    var imageUrl = json.spread_image_ref || json.card_image_ref;
    styles = styles.setIn(['image', 'backgroundImage'], "url(" + imageUrl + ")");
    if (json.has_spread) {
        styles = styles.setIn(['image', 'backgroundSize'], "cover");
    }

    styles = styles.toJS();
    dialog.content = (
        <div style={styles.container}>
            <div style={styles.image}></div>
            <div style={styles.info}>
                {_.map(Info, (info, idx) => (
                    <div key={idx} style={styles.divider}>
                        <span style={styles.header}>{info.title}</span>
                        <table style={styles.table}>
                            <tbody>
                                {_.map(info.rows, (row, idx) => {
                                    var label, value;
                                    if (_.isString(row)) {
                                        var parsed = row.split("|");
                                        label = parsed[1];
                                        value = traverseObject(card, parsed[0]);
                                    } else if (_.isObject(row)) {
                                        label = row.label;
                                        value = row.value(card);
                                    }

                                    return (
                                        <tr key={idx}>
                                            <th style={styles.th}>{label}</th>
                                            <td style={styles.td}>{value}</td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                ))}
            </div>
        </div>
    );
    dialog.actions.push(
        <FlatButton
            label="Back"
            onClick={backToSearch.apply(this)} />
        );
        dialog.title = card.card_json.name;
}

function renderSearchMenu(dialog) {
    const styles = this.styles;
    dialog.content = (
        <div>
            <TextField
                style={styles.searchField}
                hintText="Search card database"
                onChange={trackSearch.apply(this)} />
            <List>
                {renderSearchedCards.apply(this, [styles])}
            </List>
        </div>
        );
}

function renderSearchedCards(styles) {
    var cards = this.state.cards;
    return _.map(cards, (card) => (
        <ListItem
            key={card.id}
            leftAvatar={<Avatar src={card.image} style={styles.avatar} />}
            primaryText={printCardTitle(card)}
            secondaryText={card.id}
            onClick={() => this.setState({ card })}/>
        ));
}

function printCardTitle(card) {
    return "[" + parseRarity(card.rarity) + "] " + card.character.name;
}

