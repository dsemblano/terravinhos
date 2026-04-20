{{--
  Template Name: Home BravaTerra Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
  @if (! is_front_page() && ! is_home() )
    @include('partials.page-header')
  @endif
    {{-- @include('partials/home.hero') --}}
    @include('partials.content-page')
  @endwhile
@endsection
