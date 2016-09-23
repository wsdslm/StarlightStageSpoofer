export class Generator {
    constructor() {
        this._actionType = {};
    }

    generate(type, name) {
        this._actionType[type] = type;
        return (state) => {
            var obj = {};
            obj.type = type;
            if (name) obj[name] = state;
            if (state) obj.payload = state;
            return obj;
        };
    }

    actionType() {
        return this._actionType;
    }
}
