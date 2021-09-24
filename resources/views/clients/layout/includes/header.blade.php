<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('onex.page_title') }} @yield('page_title')</title>
  <link rel="icon" type="image/png" href="{{ asset(config('onex.assets_path') . '/images/onex24.png') }}" />
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/dist/css/sanspro-font.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-loading/vue-loading.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.vue_assets_path') . '/vue-sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.backend_assets_path') . '/css/onex.css') }}">
  <link rel="stylesheet" href="{{ asset(config('onex.client_assets_path') . '/dist/css/onexlte.css') }}">
  @stack('page_css')
</head>
<body class="hold-transition sidebar-mini pace-navy layout-navbar-fixed layout-fixed text-sm">
