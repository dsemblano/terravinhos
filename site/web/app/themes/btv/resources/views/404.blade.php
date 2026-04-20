@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('A página não existe. Por favor use o menu ou clique no logo', 'sage') !!}
    </x-alert>

    {{-- {!! get_search_form(false) !!} --}}
  @endif
@endsection
