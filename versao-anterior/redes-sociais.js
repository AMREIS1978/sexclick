// Introduza os endereços completos dos perfis oficiais da SEXCLICK.
const SOCIAL_URLS = {Facebook: '', X: '', Instagram: '', YouTube: '', Spotify: '', Discord: '', TikTok: ''};
document.querySelectorAll('[data-social]').forEach(button => {
 button.addEventListener('click', () => {
  const url = SOCIAL_URLS[button.dataset.social];
  if (url && /^https:\/\//i.test(url)) {window.open(url, '_blank', 'noopener,noreferrer');return;}
  const notice = document.querySelector('.social-notice');
  notice.textContent = document.documentElement.lang === 'pt-PT' ? 'Perfil em breve.' : 'Coming soon.'; notice.hidden = false;
 });
});
