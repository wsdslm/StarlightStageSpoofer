import Ajax from './ajax';

export default class Events {
    constructor(input, error) {
        this.input = input;
        this.ajax = new Ajax({ error });
    }

    doLogin(evt) {
        evt.preventDefault();
        this.ajax.doLogin(this.input.data(), (data) => {
            window.location.reload();
        });
    }

    doRegister(evt) {
        evt.preventDefault();
        this.ajax.doRegister(this.input.data(), (data) => {
            window.location.reload();
        });
    }
}
