require('./bootstrap');
window.Vue = require('vue').default;

import VueToastr from "vue-toastr";
Vue.use(VueToastr, {
    defaultTimeout: 10000,
    defaultProgressBar: true,
    defaultPosition: "toast-top-right",
    defaultStyle: { "margin-top": "60px" },
    defaultClassNames: ["animated", "zoomInUp"]
});

Vue.component('validation-toastr', require('./components/InputValidationErrorToastr.vue').default);
Vue.component('validation-list', require('./components/InputValidationErrorList.vue').default);
