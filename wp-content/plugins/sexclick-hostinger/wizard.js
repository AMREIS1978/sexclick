(() => {
 'use strict';
 const button=document.querySelector('#sch-start');
 const result=document.querySelector('#sch-result');
 if(!button || !result || typeof sexclickInstaller==='undefined')return;
 let running=false;
 button.addEventListener('click',async()=>{
  if(running)return;
  running=true;button.disabled=true;result.textContent='A preparar a instalação…';
  try {
   for(const step of sexclickInstaller.steps){
    const row=document.querySelector(`[data-step="${step}"]`);
    row.dataset.status='running';row.querySelector('.sch-state').textContent='Em execução';
    result.textContent=row.querySelector('strong').textContent+'…';
    const controller=new AbortController();
    const timer=setTimeout(()=>controller.abort(),90000);
    let response,data;
    try {
     response=await fetch(sexclickInstaller.url,{method:'POST',credentials:'same-origin',signal:controller.signal,body:new URLSearchParams({action:'sch_step',nonce:sexclickInstaller.nonce,step,overwrite:document.querySelector('#sch-overwrite').checked?'1':'',set_home:document.querySelector('#sch-home').checked?'1':'',show_catalog:document.querySelector('#sch-catalog').checked?'1':''})});
     try{data=await response.json();}catch{throw new Error('O servidor devolveu uma página em vez da resposta de instalação. Verifique a sessão WordPress e o registo PHP na Hostinger.');}
     if(!response.ok || !data.success)throw new Error(data?.data?.message || 'O alojamento recusou este passo.');
    }catch(error){
     row.dataset.status='error';row.querySelector('.sch-state').textContent='Por resolver';
     row.querySelector('.sch-detail').textContent=error.name==='AbortError'?'O passo excedeu 90 segundos. O resultado é desconhecido. Aguarde e retome: o assistente verifica o que já ficou instalado.':error.message;
     throw error;
    }finally{clearTimeout(timer);}
    row.dataset.status='done';row.querySelector('.sch-state').textContent='Concluído';row.querySelector('.sch-detail').textContent=data.data.message;
   }
   result.textContent='Instalação concluída. Pode abrir o site e continuar a preparação comercial no painel Dreamlove.';
   button.textContent='Verificar novamente';
  }catch(error){
   result.textContent=error.name==='AbortError'?'A ligação demorou mais do que o esperado. Aguarde e retome a verificação. Não volte a carregar o ZIP.':error.message;
   button.textContent='Retomar instalação';
  }finally{running=false;button.disabled=false;}
 });
})();
