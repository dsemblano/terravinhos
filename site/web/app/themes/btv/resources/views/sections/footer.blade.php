<footer class="content-info bg-preto text-primary py-6">
    <div
        class="container max-w-none">
        @php(dynamic_sidebar('sidebar-footer'))
        <div class="text-sm mt-4 flex flex-col items-center copyright border-gray-500 border-t border-solid pt-8 gap-2">
            <span class="z-10 font-bold ">{{ date('Y') }} 
                <a class="hover:underline" href="{{ home_url('/') }}">Brava Terra Vinhos</a>
                <span id="trademark" class="sup align-text-bottom">&reg;</span>
            </span>
        </div>
    </div>

</footer>
