<footer class="content-info pt-16 pb-8 bg-vinho border-t border-white/10">
    <div class="container mx-auto px-4">
        
        <div class="footer-inner text-white grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 
                    [&_.widget]:flex [&_.widget]:flex-col [&_.widget]:gap-4
                    [&_.widget_h2]:text-sm [&_.widget_h2]:font-semibold [&_.widget_h2]:uppercase [&_.widget_h2]:tracking-wider [&_.widget_h2]:text-amber-400/80
                    [&_.widget_ul]:flex [&_.widget_ul]:flex-col [&_.widget_ul]:gap-2.5 [&_.widget_ul]:text-sm
                    [&_.widget_ul_a]:text-white/80 [&_.widget_ul_a]:hover:text-white [&_.widget_ul_a]:transition-colors
                    
                    {{-- Correção específica de contraste para o widget de carrinho nativo se ele renderizar ali dentro --}}
                    [&_.amount]:text-white [&_.amount]:font-semibold
                    [&_.basket-item-count]:bg-amber-500 [&_.basket-item-count]:text-neutral-950">
            @php(dynamic_sidebar('sidebar-footer'))
        </div>

        {{-- Linha Divisória e Copyright --}}
        <div class="text-xs text-white/40 mt-16 pt-6 flex flex-col md:flex-row justify-between items-center border-t border-white/5 gap-4">
            <span class="z-10 font-medium">
                &copy; {{ date('Y') }}
                <a class="hover:underline text-white/60 hover:text-white transition-colors" href="{{ home_url('/') }}">
                    Brava Terra Vinhos
                </a>
                <span id="trademark" class="align-baseline text-[10px] opacity-70">&reg;</span>
                <span>. Todos os direitos reservados.</span>
            </span>
            
            <div class="flex items-center gap-4">
                <span class="tracking-wide">Beba com moderação.</span>
            </div>
        </div>

    </div>
</footer>