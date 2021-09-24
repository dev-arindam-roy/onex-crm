window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//Interceptors
window.axios.interceptors.request.use(req => {
    req.headers['Content-Type'] = 'application/json';
    req.headers['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    return req;
});
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    console.log('ERROR==' + error)
    return Promise.reject(error);
});