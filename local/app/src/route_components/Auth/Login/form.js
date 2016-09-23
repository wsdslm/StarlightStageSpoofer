export default function(events) {
    return {
        title: "Login",
        fields: [
            "textField:email|Email|email",
            "textField:password|Password|password",
            "checkbox:remember|Remember me"
        ],
        submit: {
            label: "Login",
            callback: events.doLogin.bind(events),
        },
        changePage: {
            label: "Register",
            path: "/register"
        }
    };
}
