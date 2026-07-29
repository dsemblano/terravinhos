import Alpine from 'alpinejs'
import focus from '@alpinejs/focus' // For better accessibility

// Initialize Alpine
window.Alpine = Alpine
Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
  const liquidButtons = document.querySelectorAll('.wp-block-button.is-liquid-fill .wp-block-button__link');

  liquidButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      const targetUrl = button.getAttribute('href');

      // Se for um link válido e não abrir em nova aba (_blank)
      if (targetUrl && button.getAttribute('target') !== '_blank') {
        e.preventDefault(); // Impede a troca imediata de página

        // Adiciona a classe de clique/preenchimento final
        button.classList.add('is-pouring');

        // Aguarda a animação terminar (600ms) para redirecionar
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 600); 
      }
    });
  });
});


import.meta.glob([
  '../images/**',
  '../fonts/**',
]);