import Alpine from 'alpinejs'
import focus from '@alpinejs/focus' // For better accessibility

// Initialize Alpine
window.Alpine = Alpine
Alpine.start()


// Arrow top
document.addEventListener("DOMContentLoaded", function() {
    //hide or show the "back to top" link

    //smooth scroll to top
    document.querySelector('.cd-top').addEventListener('click', function(event) {
        event.preventDefault();
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
});

// logocroll
document.addEventListener("DOMContentLoaded", function() {
    //hide or show the "back to top" link
    window.onscroll = function() {
        if (window.pageYOffset > 300) {
            document.querySelector('.cd-top').classList.add('cd-is-visible');
            document.querySelector('.cd-top').classList.remove('cd-fade-out');
        } else {
            document.querySelector('.cd-top').classList.remove('cd-is-visible');
        }
        if (window.pageYOffset > 1200) {
            document.querySelector('.cd-top').classList.add('cd-fade-out');
        }
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            document.getElementById("logo").classList.add("shrink", "bottom-4");
            document.getElementById("logosurname").classList.add("hidden");
            document.getElementById("logosurnamepage").classList.remove("hidden");
            document.getElementById("logosurnamepage").classList.add("block");
            
        } else {
            document.getElementById("logo").classList.remove("shrink");
            document.getElementById("logosurname").classList.remove("hidden");
            document.getElementById("logosurnamepage").classList.remove("block");
            document.getElementById("logosurnamepage").classList.add("hidden");
        }
    };
});

/**
 * Transforma selects de variação em Swatches Premium
 */
const setupSwatches = () => {
  const variationSelects = document.querySelectorAll('.variations select');

  variationSelects.forEach(select => {
    // Evita duplicar se o script rodar duas vezes
    if (select.classList.contains('swatches-initialized')) return;
    select.classList.add('swatches-initialized', 'hidden');

    const container = document.createElement('div');
    container.className = 'flex flex-wrap gap-3 my-4';

    Array.from(select.options).forEach(option => {
      if (!option.value) return; // Pula o "Escolha uma opção"

      const button = document.createElement('button');
      button.type = 'button';
      button.className = `
        relative px-5 py-2.5 rounded-full border-2 text-sm font-semibold transition-all duration-200
        hover:border-primary hover:text-primary active:scale-95
        ${option.selected 
          ? 'border-primary bg-primary/5 text-primary shadow-sm' 
          : 'border-gray-200 bg-white text-gray-600'}
      `;
      
      button.innerHTML = `<span>${option.text}</span>`;

      button.onclick = (e) => {
        e.preventDefault();
        select.value = option.value;
        
        // Dispara o evento que o WooCommerce precisa para atualizar o preço
        select.dispatchEvent(new Event('change', { bubbles: true }));

        // Atualiza o visual dos botões
        container.querySelectorAll('button').forEach(btn => {
          btn.classList.remove('border-primary', 'bg-primary/5', 'text-primary', 'shadow-sm');
          btn.classList.add('border-gray-200', 'bg-white', 'text-gray-600');
        });
        
        button.classList.add('border-primary', 'bg-primary/5', 'text-primary', 'shadow-sm');
        button.classList.remove('border-gray-200', 'bg-white', 'text-gray-600');
      };

      container.appendChild(button);
    });

    select.parentNode.insertBefore(container, select);
  });
};

// Inicia no carregamento e também se o WooCommerce atualizar as variações via AJAX
document.addEventListener('DOMContentLoaded', setupSwatches);
jQuery(document.body).on('check_variations', setupSwatches);

// Shrink banner
document.addEventListener("DOMContentLoaded", function() {
    const bannerInner = document.getElementById("banner-inner");
    const banner = document.getElementById("banner");

    if (!bannerInner) return;

    window.addEventListener("scroll", function() {
        // Fallback checks across all browser engines and structural overflow alterations
        const scrollPosition = window.scrollY 
            || window.pageYOffset 
            || document.documentElement.scrollTop 
            || document.body.scrollTop;

        // Diagnostic log: check your browser inspector console on /catalogo/ to see if this changes!
        console.log("Current Scroll Position:", scrollPosition);

        if (scrollPosition > 50) {
            banner.classList.remove('notshrink');
            banner.classList.add('shrink');
            bannerInner.classList.remove('py-4');
            bannerInner.classList.add('py-2');
        } else {
            banner.classList.remove('shrink');
            banner.classList.add('notshrink');
            bannerInner.classList.remove('py-2');
            bannerInner.classList.add('py-4');
        }
    }, { passive: true }); // passive increases performance on heavy product scroll loops
});

document.addEventListener('DOMContentLoaded', () => {
    // Procura todos os seletores de quantidade do WooCommerce
    document.querySelectorAll('.quantity').forEach(qty => {
        const input = qty.querySelector('input[type="number"]');
        if (!input) return;

        // Cria o botão de Menos
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.innerText = '−';
        minusBtn.className = 'px-3 h-full text-neutral-500 hover:text-neutral-800 transition-colors font-medium text-lg focus:outline-none';
        
        // Cria o botão de Mais
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.innerText = '+';
        plusBtn.className = 'px-3 h-full text-neutral-500 hover:text-neutral-800 transition-colors font-medium text-lg focus:outline-none';

        // Insere os botões envolvendo o input
        qty.insertBefore(minusBtn, input);
        qty.appendChild(plusBtn);

        // Listeners de clique
        minusBtn.addEventListener('click', () => {
            const val = parseInt(input.value) || 1;
            const min = parseInt(input.getAttribute('min')) || 1;
            if (val > min) {
                input.value = val - 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        plusBtn.addEventListener('click', () => {
            const val = parseInt(input.value) || 1;
            const max = parseInt(input.getAttribute('max'));
            if (!max || val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
});


import.meta.glob([
  '../images/**',
  '../fonts/**',
]);