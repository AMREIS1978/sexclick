(() => {
 'use strict';
 const dialog=document.querySelector('#sc-search-dialog');
 let opener;
 if(dialog && typeof dialog.showModal==='function') {
  document.querySelectorAll('[data-search-open]').forEach(button=>button.addEventListener('click',()=>{
   opener=button;dialog.showModal();document.body.classList.add('sc-modal-open');dialog.querySelector('input[type=search]')?.focus();
  }));
  document.querySelector('[data-search-close]')?.addEventListener('click',()=>dialog.close());
  dialog.addEventListener('click',event=>{if(event.target===dialog){const rect=dialog.getBoundingClientRect();if(event.clientX<rect.left||event.clientX>rect.right||event.clientY<rect.top||event.clientY>rect.bottom)dialog.close();}});
  dialog.addEventListener('close',()=>{document.body.classList.remove('sc-modal-open');opener?.focus();});
 } else {
  document.querySelectorAll('[data-search-open]').forEach(button=>button.addEventListener('click',()=>{if(dialog){dialog.setAttribute('open','');dialog.querySelector('input[type=search]')?.focus();}}));
  document.querySelector('[data-search-close]')?.addEventListener('click',()=>dialog?.removeAttribute('open'));
 }
 // Content remains visible if JavaScript or observers are unavailable.
 if('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion:reduce)').matches) {
  const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.animate([{transform:'translateY(18px)',opacity:.35},{transform:'none',opacity:1}],{duration:650,easing:'ease-out'});observer.unobserve(entry.target);}}),{threshold:.12});
  document.querySelectorAll('.sc-section-heading,.sc-editorial').forEach(el=>observer.observe(el));
 }
})();
