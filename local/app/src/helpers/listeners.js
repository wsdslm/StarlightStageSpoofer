import { traverseObject } from './index';

export function trackChange(obj, key, callback) {
    return (evt, val) => {
        if (val == undefined) val = evt.target.value;
        traverseObject(obj, key, val, { parents: true });
        callback && callback(obj, key, val);
    };
}

