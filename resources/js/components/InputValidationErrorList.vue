<template>
    <div v-if="errorList.length > 0" class="col-12 alert alert-danger fade show" role="alert" :data-count="errorList.length">
        <div v-for="(error,index) in errorList" :key="index">
            <li class="vc-err-li">{{error}}</li>
        </div>
    </div>
</template>

<script>
export default {
  props:{
    errorList: {
      type: Array,
      default: []
    }
  }
}
</script>

<style scoped>
.vc-err-li {
    font-size: 14px;
}
</style>