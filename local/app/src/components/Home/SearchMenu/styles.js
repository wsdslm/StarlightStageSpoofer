import Immutable from 'immutable';

export default Immutable.Map({
    drawer: Immutable.Map({
        padding: "9px",
        boxSizing: "border-box"
    }),
    dialog: Immutable.Map({
        width: "90%"
    }),
    textField: Immutable.Map({
        width: "100%"
    }),
    overlay: Immutable.Map({
        backgroundColor: "rgba(0,0,0,0.7)",
        position: "fixed",
        top: 0,
        left: "-100%",
        width: "100%",
        height: "100vh",
        opacity: 0,
        transitionProperty: "left, opacity",
        transitionDuration: "0s, 400ms",
        transitionDelay: "400ms, 0s",
        zIndex: 1000
    }),
    overlayShow: Immutable.Map({
        left: 0,
        opacity: 1,
        transitionDelay: "0s, 0s"
    }),
    paper: Immutable.Map({
        position: "absolute",
        top: "50%",
        left: "50%",
        padding: "9px",
        width: "100%",
        transform: "translate(-50%, -50%)",
        overflow: "auto",
        maxWidth: "800px",
        maxHeight: "90%",
        opacity: 0,
        transition: "opacity 400ms"
    }),
    paperShow: Immutable.Map({
        opacity: 1
    }),
    searchField: Immutable.Map({
        width: "100%"
    }),
    avatar: Immutable.Map({
        backgroundSize: "cover"
    }),
    card: Immutable.Map({
        container: Immutable.Map({
            display: "table",
            width: "100%",
            paddingTop: "24px"
        }),
        image: Immutable.Map({
            backgroundSize: "contain",
            backgroundPosition: "50% 50%",
            backgroundRepeat: "no-repeat",
            display: "block",
            width: "100%",
            height: "340px",
            margin: "0 auto"
        }),
        info: Immutable.Map({
            display: "block",
            width: "100%",
            color: "rgba(0,0,0,0.84)",
            verticalAlign: "top"
        }),
        table: Immutable.Map({
            fontSize: "10pt"
        }),
        header: Immutable.Map({
            fontWeight: 500,
            marginBottom: "9px"
        }),
        th: Immutable.Map({
            padding: "9px 0",
            borderBottom: "1px solid #eee",
            whiteSpace: "nowrap"
        }),
        td: Immutable.Map({
            padding: "9px 0 9px 18px",
            borderBottom: "1px solid #eee",
            width: "100%"
        }),
        divider: Immutable.Map({
            marginTop: "18px"
        })
    })
});
