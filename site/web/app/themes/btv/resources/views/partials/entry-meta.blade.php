<time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  <div class="text-base leading-6 text-p">
      <span>Postado em {{ the_time('j F Y') }}<br></span>
      @if ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) )
        <span>Atualizada em {{ the_modified_time('j F Y') }}</span>
      @endif
  </div>
</time>

<div class="flex flex-col lg:flex-row items-center author py-2 w-fit m-auto lg:m-0">
  <figure class="">
    <figcaption class="">
      {!! get_avatar( get_the_author_meta('ID'), 64, '', 'avatar', array('class' => 'rounded-full !m-0') ); !!}
      {{-- <img src="@asset('images/author.webp')" alt="Author" class="rounded-full" /> --}}
  </figure>
  <div class="text-ihcat p-author h-card lg:ml-8 not-prose">
    <div class="inline-block text-center">
      {{ __('Autor: ', 'sage') }}<span class="font-bold">{{ get_the_author() }}</span>
      <p class="author-bio text-sm text-center">
        @php
        echo get_the_author_meta('description');
        @endphp
      </p>
    </div>
  </div>
</div>

{{-- <p>
  <span>{{ __('By', 'sage') }}</span>
  <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card">
    {{ get_the_author() }}
  </a>
</p> --}}
