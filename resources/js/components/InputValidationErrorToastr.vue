<template>
  <div id="pageError" v-bind:data="errorObject"></div>
</template>

<script>
export default {
  props:{
    errorObject: {
      type: Array,
      default: () => { return [] }
    }
  },
  watch: {
    errorObject (errObj) {
      this.displayToastr(errObj);
    }
  },
  methods: {
    displayToastr(newErrs) {
      let errs = "<div>";
      if (newErrs.length) {
        newErrs.forEach(function (item) {
          errs += "<li class='v-onex-toastr-li'>" + item + "</li>";
        });
        errs += "</div>";
        errs += "<p><strong>Please proceed with valid data, thankyou.</strong></p>";
        this.$toastr.e(errs, "Sorry! Invalid Data");
      }
    }
  },
  mounted() {
    
  }
}
</script>

<style scoped>

</style>

