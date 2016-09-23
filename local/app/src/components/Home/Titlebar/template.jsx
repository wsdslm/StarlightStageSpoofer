import React from 'react';
import AppBar from 'material-ui/AppBar';
import * as events from './events';
import styles from './styles';

export default function() {
    var layout = this.props.layout;
    var dispatch = this.props.dispatch;
    return (
        <AppBar
            style={styles.appBar}
            title="Starlight Stage Spoofer"
            titleStyle={styles.appTitle}
            iconElementLeft={icon('reorder', dispatch, "sidebar")}
            iconElementRight={icon('search', dispatch, "search")}
            showMenuIconButton={layout.mobile} />
    );
}

function icon(icon, dispatch, eventName) {
    return (
        <i className="material-icons" style={styles.icon} onClick={events[eventName](dispatch)}>{icon}</i>
    );
};
