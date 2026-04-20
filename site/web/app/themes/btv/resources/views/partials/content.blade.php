<div class="container prose prose-xl mx-auto max-w-full">
    <article @php(post_class())>
        <header>
            <h2 class="entry-title">
                <a class="no-underline hover:underline" href="{{ get_permalink() }}">
                    {!! $title !!}
                </a>
            </h2>

            @include('partials.entry-meta')
        </header>

        <div class="entry-summary">
            @php(the_excerpt())
        </div>
    </article>

</div>
