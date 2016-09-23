import React from 'react';
import Drawer from 'material-ui/Drawer';
import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';

import Styles from './styles';
import * as events from './events';

import { trackChange } from 'helpers/listeners';
import { traverseObject } from 'helpers';
import { BASE_URL } from 'globals';

export default function() {
    var layout = this.props.layout;
    var dispatch = this.props.dispatch;
    var styles = Styles;
    if (!layout.mobile) {
        styles = styles.mergeIn(['drawer'], styles.get('drawerDesktop'));
    }

    styles = styles.toJS();
    return (
        <Drawer
            docked={!layout.mobile}
            width={256}
            containerStyle={styles.drawer}
            open={layout.sidebar || !layout.mobile}
            onRequestChange={events.setSidebar(dispatch)}>
            {renderContent.apply(this, [styles])}
        </Drawer>
    );
}

function renderContent(styles) {
    var login = this.props.login;
    var dispatch = this.props.dispatch;

    if (!login.user) {
        return (
            <div>Loading...</div>
        );
    }

    var user = login.game_user;
    var settings = user.settings_json;

    const settingsUpdater = function(key) {
        return {
            value: traverseObject(settings, key),
            onChange: trackChange(settings, key, events.updateSettings(user))
        };
    };

    return (
        <div>
            {header(styles, "Home", false)}
            {header(styles, login.user.name, false)}
            <div>{user.viewer_id}</div>
            {textField(styles, "Filter cards", events.trackSearch(dispatch))}
            {header(styles, "Settings")}
            {checkbox(styles, "Modify cards", settingsUpdater('modify_cards'))}
            {checkbox(styles, "Modify gacha", settingsUpdater('gacha.enabled'))}
            {textField(styles, "Gacha card ID", settingsUpdater('gacha.card_id'))}
            {checkbox(styles, "New gacha", settingsUpdater('gacha.new'))}
            <div style={styles.divider}></div>
            <a href={BASE_URL + "/auth/logout"} style={styles.header}>Logout</a>
        </div>
    );
}

function header(styles, text, divider=true) {
    if (divider) {
        return (
            <div style={styles.divider}>
                <span style={styles.header}>{text}</span>
            </div>
        );
    } else {
        return (
            <div>
                <span style={styles.header}>{text}</span>
            </div>
        );
    }
}

function textField(styles, label, value, props) {
    if (_.isObject(value)) {
        props = value;
        value = props.value;
        delete props.value;
    }

    var onChange;
    if (_.isFunction(props)) {
        onChange = props;
        props = {};
    } else {
        props = props || {};
        onChange = props.onChange || _.noop;
        delete props.onChange;
    }

    return (
        <TextField
            style={styles.textField}
            floatingLabelText={label}
            onChange={onChange}
            defaultValue={value || ""}
            { ...props } />
    );
}

function checkbox(styles, label, checked, props) {
    if (_.isObject(checked)) {
        props = checked;
        checked = props.value || false;
        delete props.value;
    }

    var onCheck;
    if (_.isFunction(props)) {
        onCheck = props;
        props = {};
    } else {
        props = props || {};
        onCheck = props.onChange || _.noop;
        delete props.onChange;
    }

    return (
        <Checkbox
            style={styles.checkbox}
            label={label}
            defaultChecked={checked || false}
            onCheck={onCheck}
            { ...props } />
    );
}
