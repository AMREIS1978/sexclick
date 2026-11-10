(() => {
 'use strict';
 const dialog=document.querySelector('#sc-search-dialog');
 let opener;
 if(dialog && typeof dialog.showModal==='function') {
  document.querySelectorAll('[data-search-open]').forEach(button=>button.addEventListener('click',()=>{
   opener=button;if(!dialog.open)dialog.showModal();document.body.classList.add('sc-modal-open');dialog.querySelector('input[type=search]')?.focus();
  }));
  document.querySelector('[data-search-close]')?.addEventListener('click',()=>dialog.close());
  dialog.addEventListener('click',event=>{if(event.target===dialog){const rect=dialog.getBoundingClientRect();if(event.clientX<rect.left||event.clientX>rect.right||event.clientY<rect.top||event.clientY>rect.bottom)dialog.close();}});
  dialog.addEventListener('close',()=>{document.body.classList.remove('sc-modal-open');opener?.focus();});
 } else {
  const close=()=>{dialog?.removeAttribute('open');document.body.classList.remove('sc-modal-open');opener?.focus();};
  document.querySelectorAll('[data-search-open]').forEach(button=>button.addEventListener('click',()=>{if(dialog){opener=button;dialog.setAttribute('open','');document.body.classList.add('sc-modal-open');dialog.querySelector('input[type=search]')?.focus();}}));
  document.querySelector('[data-search-close]')?.addEventListener('click',close);
  document.addEventListener('keydown',event=>{if(event.key==='Escape' && dialog?.hasAttribute('open'))close();});
 }
 // Content remains visible if JavaScript or observers are unavailable.
 if('IntersectionObserver' in window && 'animate' in Element.prototype && !matchMedia('(prefers-reduced-motion:reduce)').matches) {
  const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.animate([{transform:'translateY(18px)',opacity:.35},{transform:'none',opacity:1}],{duration:650,easing:'ease-out'});observer.unobserve(entry.target);}}),{threshold:.12});
  document.querySelectorAll('.sc-section-heading,.sc-editorial').forEach(el=>observer.observe(el));
 }
})();
