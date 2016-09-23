export default function(events) {
    return {
        title: "Register",
        fields: [
            "textField:viewer_id|User ID",
            "textField:name|Name",
            "textField:email|Email|email",
            "textField:password|Password|password",
            "textField:password_confirmation|Confirm Password|password"
        ],
        submit: {
            label: "Register",
            callback: events.doRegister.bind(events),
        },
        changePage: {
            label: "Login",
            path: "/login"
        }
    };
}
