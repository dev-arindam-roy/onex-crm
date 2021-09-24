@extends('administrators.layout.layout')

@section('page_title', ' | Dashboard')

@section('page_content')
<div class="row mb-2" id="dashboard" v-cloak>
  @include('onex.loading')
  <div class="row">
    <div class="col-sm-6">
      <h5 class="section-header">Dashboard</h5>
    </div>
    <div class="col-sm-6"></div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
        <div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page_js')
<script>
let appVue = new Vue({
  el: '#dashboard',
  data() {
    return {
      isLoading: true,
    }
  },
  watch: {
        
  },
  computed: {
    
  },
  methods: {
    
  },
  mounted() {
    this.isLoading = false;
  }
});
</script>
@endpush


