(() => {
  const launcher = document.getElementById('amp-chat-launcher');
  const panel = document.getElementById('amp-chat-panel');
  const close = document.getElementById('amp-chat-close');
  const log = document.getElementById('amp-chat-log');
  const form = document.getElementById('amp-chat-form');
  const input = document.getElementById('amp-chat-input');
  const send = form?.querySelector('button[type="submit"]');
  const suggestions = document.getElementById('amp-chat-suggestions');
  if (!launcher || !panel || !form) return;
  let history = [];
  let pending = false;
  function setOpen(open) {
    panel.hidden = !open;
    launcher.setAttribute('aria-expanded', String(open));
    if (open) input.focus(); else launcher.focus();
  }
  launcher.addEventListener('click', () => setOpen(panel.hidden));
  close.addEventListener('click', () => setOpen(false));
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !panel.hidden) setOpen(false); });
  function bubble(content, cls) {
    const el = document.createElement('div');
    el.className = 'amp-chat-message ' + cls;
    el.textContent = content; // no injection of model content as HTML
    log.appendChild(el);
    log.scrollTop = log.scrollHeight;
    return el;
  }
  async function ask(message) {
    if (pending || !message || message.length > 800) return;
    suggestions.hidden = true;
    bubble(message, 'customer');
    input.value = '';
    pending = true; send.disabled = true;
    const loading = bubble('Typing…', 'assistant');
    try {
      const r = await fetch('ai-chat.php', { method: 'POST', headers: {'Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({message, history: history.slice(-10)}) });
      const data = await r.json();
      if (!r.ok || typeof data.reply !== 'string') throw new Error(data.error || 'The assistant is temporarily unavailable.');
      loading.textContent = data.reply;
      history.push({role:'user',content:message},{role:'assistant',content:data.reply});
      history = history.slice(-10);
    } catch (e) {
      loading.textContent = 'Sorry, I could not connect right now. Please call (224) 276-1821 or visit our Request Service page.';
    } finally { pending = false; send.disabled = false; input.focus(); log.scrollTop = log.scrollHeight; }
  }
  form.addEventListener('submit', e => { e.preventDefault(); ask(input.value.trim()); });
  suggestions?.querySelectorAll('button[data-question]').forEach(btn => btn.addEventListener('click', () => ask(btn.dataset.question)));
})();
