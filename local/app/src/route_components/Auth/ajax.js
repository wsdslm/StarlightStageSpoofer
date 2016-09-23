import { BASE_URL } from 'globals';
import Immutable from 'immutable';

const Config = Immutable.Map({
    type: "POST",
    error: console.error.bind(console)
});

export default class Ajax {
    constructor(config={}) {
        this.config = Config.merge(Immutable.Map(config));
    }

    doAjax(config) {
        config = this.config.merge(Immutable.Map(config));
        $.ajax(config.toJS());
    }

    doLogin(data, callback) {
        this.doAjax({
            url: BASE_URL + "/auth/login",
            data: $.param(data),
            success: callback || _.noop
        });
    }

    doRegister(data, callback) {
        this.doAjax({
            url: BASE_URL + "/auth/register",
            data: $.param(data),
            success: callback || _.noop
        });
    }
}


