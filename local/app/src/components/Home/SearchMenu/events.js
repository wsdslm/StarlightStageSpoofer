import { debounce } from 'helpers';
import { BASE_API_URL } from 'globals';
import { setSearchMenu } from 'actions/layout';
import { setSearch } from 'actions/pages/home';

export function closeOverlay() {
    var prevKeyListener;
    var dispatch = this.props.dispatch;
    var onOverlay = true;

    function closeMenu() {
        dispatch(setSearchMenu(false));
    }

    var self = {};

    self.bindKeyListener = () => {
        prevKeyListener = document.onkeydown;
        document.onkeydown = (evt) => {
            if (evt.keyCode == 27) {
                closeMenu();
                document.onkeydown = prevKeyListener;
            }
        };
    };

    self.onClickClose = (evt) => {
        onOverlay && closeMenu();
    };

    self.onHover = () => onOverlay = false;
    self.onOut = () => onOverlay = true;

    return self;
}

export function trackSearch() {
    var dispatch = this.props.dispatch;
    return _.debounce((evt, val) => {
        if (_.isEmpty(val)) {
            this.setState({ cards: [] });
            return;
        }

        $.ajax({
            url: BASE_API_URL + "/search/cards?q=" + encodeURIComponent(val),
            dataType: "json",
            success: (cards) => this.setState({ cards }),
            error: console.error.bind(console)
        });
    }, 300);
}

export function backToSearch() {
    return () => {
        this.setState({
            cards: this.state.cards,
            card: null
        });
    };
}
