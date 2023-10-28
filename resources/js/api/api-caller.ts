const defaultOptions = {
    method: 'get',
    params: {},
    data: {},
}

export const ajaxLoaderOptions = {
    // request delay in seconds, e.g. 0.5
    delayRequest: 0,
    logRequests: false,
}

function apiCall(url: String, options: Object) {
    const newOptions = {...defaultOptions, ...options};
    newOptions.url = url;
    let method = (newOptions.method ?? 'get').toString().toLowerCase()
    let isDelete = 'delete' === method
    if(ajaxLoaderOptions.logRequests) {
        console.log('apiCall', url, newOptions);
    }
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            axios(newOptions).then((response: AxiosResponse) => {
                if (ajaxLoaderOptions.logRequests) {
                    console.log('apiCall', url,'response',response.status, response.data);
                }
                if (isDelete && 204 === response.status) {
                    if(ajaxLoaderOptions.logRequests) {
                        console.log('apiCall', url, 'delete & 204');
                    }
                    resolve();
                    return;
                }
                if (response.data === null) {
                    if(ajaxLoaderOptions.logRequests) {
                        console.log('apiCall', url, 'response.data === null or undefined');
                    }
                    reject('Internal server error');
                    return;
                }
                resolve(response.data);
            }).catch((reason: AxiosError) => {
                if(ajaxLoaderOptions.logRequests) {
                    console.log('apiCall', url, 'error', reason);
                }
                if (isDelete && 404 == reason.response.status) {
                    if(ajaxLoaderOptions.logRequests) {
                        console.log('apiCall', url, 'delete & 404');
                    }
                    resolve();
                    return;
                }
                reject("Unknown error, please try again later.");
            });
        }, ajaxLoaderOptions.delayRequest * 1000);
    });
}

export function apiPost(url: String, data: Object) {
    return apiCall(url, {method: 'post', data: data});
}

export function apiDelete(url: String, data: Object) {
    return apiCall(url, {method: 'delete', data: data});
}

export function apiPut(url: String, data: Object) {
    return apiCall(url, {method: 'put', data: data});
}

export function apiGet(url: String, params: Object) {
    return apiCall(url, {method: 'get', params: params});
}
