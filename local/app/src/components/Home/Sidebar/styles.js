import Immutable from 'immutable';

export default Immutable.Map({
    drawer: Immutable.Map({
        padding: "9px",
        boxSizing: "border-box"
    }),
    drawerDesktop: Immutable.Map({
        paddingTop: "73px",
        zIndex: 999
    }),
    checkbox: Immutable.Map({
        marginBottom: "9px"
    }),
    textField: Immutable.Map({
        width: "100%",
        marginTop: "-15px"
    }),
    header: Immutable.Map({
        display: "block",
        fontSize: "12pt",
        paddingBottom: "9px",
        fontWeight: 500
    }),
    divider: Immutable.Map({
        marginTop: "9px",
        paddingTop: "9px"
    })
});
