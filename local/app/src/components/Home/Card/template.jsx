import React from 'react';
import Paper from 'material-ui/Paper';
import TextField from 'material-ui/TextField';
import FlatButton from 'material-ui/FlatButton';

import { traverseObject } from 'helpers';
import { trackChange } from 'helpers/listeners';
import * as events from './events';
import styles from './styles';
import { forms, tagHandlers } from './forms';

export default function() {
    var gameCard = this.state.gameCard;
    var card = gameCard.card || {};
    var resetCallback = (gameCard) => {
        this.setState({
            gameCard
        });
    };

    return (
        <Paper style={styles.paper}>
            <div>
                <img style={styles.img} src={cardImage(gameCard)} />
            </div>
            <div style={styles.info}>
                <table style={styles.table}>
                    <tbody>
                        {renderForm.apply(this, [gameCard])}
                    </tbody>
                </table>
                <div style={styles.cls}></div>
                <div style={styles.buttonWrapper}>
                    <FlatButton
                        style={styles.button}
                        primary={true}
                        label="Save"
                        onClick={events.doSave(gameCard)} />
                    <FlatButton
                        style={styles.button}
                        label="Reset"
                        onClick={events.doReset(gameCard, resetCallback)} />
                </div>
            </div>
        </Paper>
    );
}

function cardImage(gameCard) {
    var modified = gameCard.modified_json || {};
    var card_id = modified.card_id || gameCard.card_id;
    return "https://hoshimoriuta.kirara.ca/card/" + card_id + ".png";
}

function renderForm(gameCard) {
    var original = gameCard.card_json;
    var modified = gameCard.modified_json || {};

    return _.map(forms, (form, idx) => {
        var parsed = form.split("|");
        var key = parsed[0];
        var label = parsed[1];
        var tags = parsed.length >= 3 ? parsed[2].split(",") : [];

        var values = {
            original: traverseObject(original, key),
            modified: traverseObject(modified, key)
        };

        if (values.original === undefined) values.original = gameCard[key];

        var value = values.modified;
        if (value === undefined) value = values.original;
        if (value != values.original) label += "*";

        tags.forEach((tag) => {
            var args = tag.split(":");
            var name = args[0];
            if (!tagHandlers[name]) return;
            value = tagHandlers[name](value, args);
        });

        var changeCallback = updateState(this, gameCard);

        return (
            <tr key={idx}>
                <th style={styles.th}>{label}</th>
                <td style={styles.td}>
                    <input
                        type="text"
                        style={styles.textInput}
                        value={value}
                        disabled={!_.includes(tags, "editable")}
                        onChange={trackChange(gameCard, "modified_json." + key, changeCallback)} />
                </td>
            </tr>
        );
    });
}

function updateState(self, gameCard) {
    return () => {
        self.setState({
            gameCard
        });
    };
}
