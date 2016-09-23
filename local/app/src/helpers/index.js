import { MOBILE_WIDTH_PX } from 'globals';

export function traverseObject(obj, keys, value, options={}) {
    if (typeof keys == "string") {
        keys = keys.split(".");
    }

    var current = obj;
    var parent = null;
    var lastKey = null;

    keys.forEach((key) => {
        if (!current) {
            if (!options.parents) return false;
            parent[lastKey] = current = {};
        }
        parent = current;
        current = parent[key];
        lastKey = key;
    });

    if (value !== undefined) {
        parent[lastKey] = value;
    }

    return current;
}

export function inArray(needle, hay) {
    return hay.indexOf(needle) > -1;
}

export function isMobile() {
    return window.innerWidth < MOBILE_WIDTH_PX;
}

export function debounce(timeout, callback, interval=300) {
    if (timeout) clearTimeout(timeout);
    return setTimeout(callback, interval);
}

