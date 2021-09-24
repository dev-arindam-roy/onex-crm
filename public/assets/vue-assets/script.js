Vue.use(VueLoading);
Vue.component('loading', VueLoading);

Vue.use(window.vuelidate.default);
const {required, requiredIf, numeric, minValue, maxValue, minLength, maxLength, email, sameAs, helpers} = window.validators;

const regxAlfaWithSpace = (value, vm) => {
    if (value == '') return true
    return /^[A-Z a-z]+$/.test(value)
}

const regxAlfaWithOutSpace = (value, vm) => {
    if (value == '') return true
    return /^[A-Za-z0-9]+$/.test(value)
}

const regxOrganizationName = (value, vm) => {
    if (value == '') return true
    return /^[A-Za-z 0-9.]+$/.test(value)
}

const regxBrandName = (value, vm) => {
    if (value == '') return true
    return /^[A-Za-z 0-9.@&]+$/.test(value)
}
  
const regxEmailAddress = (value, vm) => {
    if (value == '') return true
    return /^([a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)
}

const regxNumeric = (value, vm) => {
    if (value == '') return true
    return /^[0-9]+$/.test(value)
}

const regxMobileNumber = (value, vm) => {
    if (value == '') return true
    return /^[0-9]{10,12}$/.test(value)
}

const regxPassword = (value, vm) => {
    if (value == '') return true
    return /^[a-zA-Z0-9!#\?\^\[\]{}+=._-]{8,32}$/.test(value)
}
