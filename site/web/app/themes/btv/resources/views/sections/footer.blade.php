<footer class="content-info py-6 bg-vinho">
    <div class="container">
        <div class="footer-inner text-white">
            @php(dynamic_sidebar('sidebar-footer'))
        </div>
        <div class="text-sm text-white mt-4 flex flex-col items-center copyright border-gray-500 border-t border-solid pt-8 gap-2">
            <span class="z-10 font-bold ">{{ date('Y') }}
                <a class="hover:underline" href="{{ home_url('/') }}">Brava Terra Vinhos</a>
                <span id="trademark" class="sup align-text-bottom">&reg;</span>
            </span>
        </div>
    </div>
</footer>
