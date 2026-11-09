(() => {
 const reduce = matchMedia('(prefers-reduced-motion: reduce)');
 const header = document.querySelector('header');
 const hero = document.querySelector('.hero');
 let previous = window.scrollY, scheduled = false;
 function update() {
  const y = window.scrollY;
  header?.classList.toggle('scroll-away', y > 160 && y > previous + 3 && !document.querySelector('header:focus-within'));
  if (y < 80 || y < previous - 3) header?.classList.remove('scroll-away');
  if (hero && !reduce.matches) {
   const progress = Math.min(1, Math.max(0, y / hero.offsetHeight));
   hero.style.setProperty('--hero-shift', `${Math.round(progress * 65)}px`);
  }
  previous = y; scheduled = false;
 }
 addEventListener('scroll', () => {if (!scheduled) {scheduled = true;requestAnimationFrame(update);}}, {passive:true});
 const targets = document.querySelectorAll('.statement,.cat,.section-title,.card,.promise,footer');
 if ('IntersectionObserver' in window && !reduce.matches) {
  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
   if (entry.isIntersecting) {entry.target.classList.add('revealed');observer.unobserve(entry.target);}
  }), {threshold:.08});
  targets.forEach(el => {el.classList.add('scroll-reveal');observer.observe(el);});
  // Product cards are rebuilt when the language or catalogue changes.
  const products = document.querySelector('.products');
  if (products) new MutationObserver(() => products.querySelectorAll('.card:not(.scroll-reveal)').forEach(el => {el.classList.add('scroll-reveal');observer.observe(el);})).observe(products,{childList:true});
 }
 reduce.addEventListener('change', () => {if (reduce.matches) {document.querySelectorAll('.scroll-reveal').forEach(el=>el.classList.add('revealed'));hero?.style.removeProperty('--hero-shift');}});
 update();
})();
