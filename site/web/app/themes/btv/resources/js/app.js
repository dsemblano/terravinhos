import Alpine from 'alpinejs'
import focus from '@alpinejs/focus' // For better accessibility

// import partytownSnippet from '@qwik.dev/partytown/integration'

// const snippetText = partytownSnippet()

// Initialize Alpine
window.Alpine = Alpine
Alpine.start()

// Wait for the page to load (including LCP)
// document.addEventListener('DOMContentLoaded', () => {
//   const sections = document.querySelectorAll('.animate_scroll');

//   // Configure Intersection Observer
//   const observer = new IntersectionObserver((entries) => {
//     entries.forEach((entry) => {
//       if (entry.isIntersecting) {
//         // Step 1: Make element visible (remove opacity:0)
//         entry.target.classList.add('animated');
        
//         // Step 2: Apply Animate.css effect after a tiny delay
//         setTimeout(() => {
//           entry.target.classList.add('animate__animated', 'animate__fadeInLeft');
//         }, 10); // Short delay ensures CSS transition applies
        
//         observer.unobserve(entry.target);
//       }
//     });
//   }, { threshold: 0.1 });

//   // Observe all target sections
//   sections.forEach((section) => {
//     observer.observe(section);
//   });
// });

// cd top
// document.addEventListener("DOMContentLoaded", function() {
//     //hide or show the "back to top" link

//     //smooth scroll to top
//     document.querySelector('.cd-top').addEventListener('click', function(event) {
//         event.preventDefault();
//         window.scrollTo({top: 0, behavior: 'smooth'});
//     });
// });

// animate
const element = document.querySelector('h1');
element.classList.add('animate__animated', 'animate__flipInX');

// run on load and resize (debounce in production)
// function setVh() {
//   let vh = window.innerHeight * 0.01;
//   document.documentElement.style.setProperty('--vh', `${vh}px`);
// }
// setVh();
// window.addEventListener('resize', setVh);


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



import.meta.glob([
  '../images/**',
  '../fonts/**',
]);
