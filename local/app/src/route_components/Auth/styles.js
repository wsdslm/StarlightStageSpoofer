import Immutable from 'immutable';

export default Immutable.Map({
    paper: Immutable.Map({
        position: "absolute",
        top: "50%",
        left: "50%",
        width: "90%",
        maxWidth: "320px",
        padding: "9px 18px",
        transform: "translate(-50%, -50%)",
        textAlign: "center"
    }),
    header: Immutable.Map({
        fontSize: "12pt",
        textAlign: "center"
    }),
    textField: Immutable.Map({
        width: "100%"
    }),
    checkbox: Immutable.Map({
        margin: "9px 0",
        textAlign: "left"
    }),
    buttonContainer: Immutable.Map({
        marginTop: "9px"
    })
});
