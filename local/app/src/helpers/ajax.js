import { BASE_API_URL, OBJECT_RESOURCE_PATH } from 'globals';

export function fetchObject(obj, options) {
    var paths = [obj];
    if (options.id) paths.push(options.id);

    var params = {};
    if (options['with']) {
        var _with = options['with'];
        if (Array.isArray(_with)) _with = _with.join(",");
        params['with'] = _with;
    }

    $.ajax({
        url: createObjectUrl(paths) + "?" + $.param(params),
        type: "GET",
        dataType: "json",
        success: options.success || _.noop,
        error: console.error.bind(console)
    });
}

export function putObject(obj, id, data, options={}) {
    $.ajax({
        url: createObjectUrl([obj, id]),
        type: "PUT",
        headers: {
            "Content-Type": "application/json"
        },
        data: JSON.stringify(data),
        dataType: "json",
        success: options.success || _.noop,
        error: console.error.bind(console)
    });
}

function createObjectUrl(path) {
    var parsed = [
        BASE_API_URL, OBJECT_RESOURCE_PATH
    ];

    if (Array.isArray(path)) parsed = parsed.concat(path);
    else parsed.push(path);

    return parsed.join("/");
}
