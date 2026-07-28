import Alpine from 'alpinejs'
import focus from '@alpinejs/focus' // For better accessibility

// Initialize Alpine
window.Alpine = Alpine
Alpine.start()



import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

/* 1. ESTADO INICIAL: Botão Base (Apenas o Vidro) */
.wp-block-button.is-liquid-fill .wp-block-button__link {
  @apply px-8 py-5 text-lg font-bold;
  position: relative !important;
  overflow: hidden !important;
  z-index: 1 !important;
  display: inline-block !important;
  
  border-radius: 14px 14px 44px 44px !important;
  background: rgba(255, 255, 255, 0.12) !important;
  backdrop-filter: blur(12px) !important;
  -webkit-backdrop-filter: blur(12px) !important;
  border: 1.5px solid rgba(255, 255, 255, 0.4) !important;
  box-shadow: 
    inset 0 1px 2px rgba(255, 255, 255, 0.6),
    inset 0 -2px 4px rgba(0, 0, 0, 0.15),
    0 8px 32px 0 rgba(0, 0, 0, 0.2) !important;
  color: #ffffff !important;

  /* Posição de repouso e transição de saída do botão */
  transform: translateY(0) scale(1) !important;
  transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1) !important;
}

/* 2. ESTADO INICIAL: O Vinho (Totalmente escondido e sem animação) */
.wp-block-button.is-liquid-fill .wp-block-button__link::before,
.wp-block-button.is-liquid-fill .wp-block-button__link::after {
  content: "";
  position: absolute;
  left: -50%;
  width: 200%;
  height: 350%;
  z-index: -1;
  
  /* Escondido lá embaixo e totalmente parado */
  top: 120%; 
  transform: rotate(0deg); 
  
  /* Transição que atua APENAS quando o mouse sai (desce e para de girar) */
  transition: top 0.7s ease-in-out, transform 0.7s ease-in-out !important;
}

.wp-block-button.is-liquid-fill .wp-block-button__link::before {
  border-radius: 42%;
  background: #3b0a11;
}

.wp-block-button.is-liquid-fill .wp-block-button__link::after {
  border-radius: 38%;
  background: linear-gradient(180deg, var(--wp--preset--color--vinho, #722f37) 0%, #4a0d16 100%) !important;
}

/* 3. HOVER & ACTIVE: A Animação Acontece */
.wp-block-button.is-liquid-fill:hover .wp-block-button__link,
.wp-block-button.is-liquid-fill:active .wp-block-button__link {
  color: #ffffff !important;
  border-color: rgba(255, 255, 255, 0.7) !important;
  transform: translateY(-8px) scale(1.02) !important;
  box-shadow: 
    inset 0 1px 4px rgba(255, 255, 255, 0.8),
    0 20px 40px rgba(114, 47, 55, 0.45) !important;
}

/* O vinho sobe e os keyframes de giro são injetados */
.wp-block-button.is-liquid-fill:hover .wp-block-button__link::before,
.wp-block-button.is-liquid-fill:active .wp-block-button__link::before {
  top: 35%;
  animation: wine-spin 6s linear infinite !important;
  transition: top 0.8s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.wp-block-button.is-liquid-fill:hover .wp-block-button__link::after,
.wp-block-button.is-liquid-fill:active .wp-block-button__link::after {
  top: 40%;
  animation: wine-spin 4s linear infinite !important;
  transition: top 0.9s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* O clique na taça */
.wp-block-button.is-liquid-fill:active .wp-block-button__link {
  transform: translateY(-12px) scale(0.98) !important;
  transition: transform 0.1s ease !important;
}

/* 4. KEYFRAMES */
@keyframes wine-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}