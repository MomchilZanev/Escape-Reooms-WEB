async function fetchGet(controller, action, parameters = {}, successCallback = textCallback, errorCallback = defaultErrorCallback) {
    try {
        const url = buildGetUrl(controller, action, parameters);
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error("Error: " + response.status);
        }

        return await successCallback(response);
    } catch (error) {
        errorCallback(error);
    }
}

async function fetchPost(controller, action, postData = {}, successCallback = textCallback, errorCallback = defaultErrorCallback) {
    try {
        const url = buildBaseUrl(controller);
        const request = buildPostRequest(action, postData);
        const response = await fetch(url, request);

        if (!response.ok) {
            throw new Error("Error: " + response.status);
        }

        return await successCallback(response);
    } catch (error) {
        errorCallback(error);
    }
}

function buildPostRequest(action, postData = {}) {
    var body = "action=" + action;
    for (var key in postData) {
        body += "&" + key + "=" + encodeURIComponent(JSON.stringify(postData[key]));
    }

    return {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: body
    };
}

function buildGetUrl(controller, action, parameters = {}) {
    parameters["action"] = action;
    return buildBaseUrl(controller) + new URLSearchParams(parameters).toString();
}

function buildBaseUrl(controller) {
    return window.origin + "/escaperooms/src/controllers/" + controller + ".php?";
}

function defaultErrorCallback(error) {
    console.error(error);
}

async function blobCallback(response) {
    return response.blob();
}

async function jsonCallback(response) {
    return response.json();
}

async function textCallback(response) {
    return response.text();
}