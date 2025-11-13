<!-- salva como fogi_widget_page.php (ou .html) no teu site -->
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Site + FOGi</title>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<style>
  :root{ --azul:#38a5ff; --bg:#f7fbff; --card:#fff; --muted:#6b7280; --glass: rgba(255,255,255,0.85);}
  *{box-sizing:border-box}
  body{margin:0;font-family:Inter, Poppins, system-ui, -apple-system, "Segoe UI", Roboto, Arial;color:#111;background:var(--bg)}
  header.site-header{display:flex;align-items:center;justify-content:space-between;padding:12px 20px;background:linear-gradient(90deg,#fff,#f8fbff);box-shadow:0 4px 18px rgba(2,6,23,.04)}
  .brand{display:flex;gap:12px;align-items:center}
  .logo-pill{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#ff88aa,#a56bff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px}
  .site-title{font-weight:700;font-size:1rem}
  nav{display:flex;gap:10px;align-items:center}
  .btn{background:var(--azul);color:#fff;border:0;padding:8px 12px;border-radius:10px;cursor:pointer;font-weight:600}
  .btn.secondary{background:#fff;color:var(--azul);border:1px solid rgba(56,165,255,.15)}
  main{padding:28px;max-width:1100px;margin:0 auto}
  .hero{background:var(--card);border-radius:12px;padding:28px;box-shadow:0 10px 30px rgba(2,6,23,.05)}
  h1{margin:0 0 8px;font-size:1.4rem}
  p.lead{margin:0;color:var(--muted)}

  /* Modal / iframe */
  #fogi-modal{position:fixed;inset:0;display:none;z-index:2000;align-items:center;justify-content:center}
  #fogi-backdrop{position:absolute;inset:0;background:rgba(2,6,23,.45)}
  #fogi-panel{position:relative;width:94%;max-width:1100px;height:82vh;border-radius:12px;overflow:hidden;background:var(--card);box-shadow:0 30px 80px rgba(2,6,23,.35);display:flex;flex-direction:column}
  #fogi-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #eef6ff;background:linear-gradient(180deg,#ffffff,#fbfdff)}
  #fogi-header .title{display:flex;gap:10px;align-items:center}
  #fogi-header .title b{font-size:1rem}
  #fogi-close{background:#fff;border:0;padding:8px 10px;border-radius:10px;cursor:pointer;box-shadow:0 6px 18px rgba(2,6,23,.06)}
  #fogi-iframe{flex:1;border:0;width:100%;height:100%}

  /* mobile tweaks */
  @media (max-width:720px){
    #fogi-panel{width:98%;height:88vh}
  }
</style>
</head>
<body>

<header class="site-header">
  <div class="brand">
    <div class="logo-pill">FOG</div>
    <div>
      <div class="site-title">Studio / FOAG</div>
      <div style="font-size:.85rem;color:var(--muted)">Seu site — integrado com a FOGi</div>
    </div>
  </div>

  <nav>
    <a href="/" class="btn secondary" style="text-decoration:none;padding:8px 10px;font-weight:600">Home</a>
    <button id="open-fogi" class="btn">Abrir FOGi</button>
  </nav>
</header>

<main>
  <section class="hero">
    <h1>Bem-vinde — FOGi pronta pra te ajudar</h1>
    <p class="lead">Clica no botão FOGi no topo para abrir a tutora. Quando quiser voltar, usa o botão “Sair” dentro do chat.</p>
  </section>
</main>

<!-- Modal -->
<div id="fogi-modal" aria-hidden="true">
  <div id="fogi-backdrop" role="button" tabindex="0"></div>
  <div id="fogi-panel" role="dialog" aria-modal="true" aria-label="Chat FOGi">
    <div id="fogi-header">
      <div class="title"><div class="logo-pill" style="width:36px;height:36px">FOGi</div><div><b>FOGi — Tutora</b><div style="font-size:.85rem;color:var(--muted)">Ajuda em estudos, ENEM e ODS 4</div></div></div>
      <div style="display:flex;gap:8px;align-items:center">
        <!-- opcional: enviar info pro iframe via query -->
        <button id="fogi-close" aria-label="Fechar">Sair</button>
      </div>
    </div>
    <iframe id="fogi-iframe" src="about:blank" title="FOGi Chat"></iframe>
  </div>
</div>

<script>
  // CONFIG: ajusta se usa ProxyPass (ex.: '/fogi') ou rota direta `http://127.0.0.1:5000`
  const FOGI_ORIGIN = "http://127.0.0.1:5000"; // se usar proxy, colocar '/fogi' no iframe.src abaixo
  const IFRAME_PATH = FOGI_ORIGIN; // pode trocar para '/fogi' se fizer ProxyPass

  const openBtn = document.getElementById('open-fogi');
  const modal = document.getElementById('fogi-modal');
  const backdrop = document.getElementById('fogi-backdrop');
  const closeBtn = document.getElementById('fogi-close');
  const iframe = document.getElementById('fogi-iframe');

  // Abre modal e seta src (passa query opcional com user)
  openBtn.addEventListener('click', () => {
    // se quiser passar usuário, acrescenta ?user=Alice
    const user = encodeURIComponent(window.USER_NAME || "visitante");
    iframe.src = IFRAME_PATH + "?user=" + user;
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden","false");
    document.body.style.overflow = "hidden";
  });

  function closeModal() {
    modal.style.display = "none";
    modal.setAttribute("aria-hidden","true");
    document.body.style.overflow = "";
    // limpa iframe para liberar memória/processo
    iframe.src = "about:blank";
  }

  closeBtn.addEventListener('click', () => {
    // manda um evento pro iframe antes de fechar (opcional)
    try {
      iframe.contentWindow.postMessage({ type: "PARENT_CLOSING" }, "*");
    } catch(e){}
    closeModal();
  });

  backdrop.addEventListener('click', closeModal);

  // ESC para fechar
  window.addEventListener('keydown', (e) => {
    if(e.key === "Escape" && modal.style.display === "flex") closeModal();
  });

  // Escuta mensagens vindas do iframe (FOGi)
  window.addEventListener('message', (ev) => {
    // Em produção: verificar ev.origin === FOGI_ORIGIN
    const msg = ev.data;
    if (!msg || typeof msg !== 'object') return;
    if (msg.type === 'FOGI_CLOSE') {
      closeModal();
      // opcional: redirecionar pro site ou fazer ação específica
      // window.location.href = '/minha-rota';
    }
    if (msg.type === 'FOGI_SEND_TO_PARENT') {
      // Exemplo: iframe pede para o site abrir seção, etc.
      console.log("FOGi pediu:", msg.payload);
    }
  }, false);

  // Accessibility fix: focus trap light
  modal.addEventListener('transitionend', () => {
    if(modal.style.display === "flex") iframe.focus();
  });

  // fallback: tenta abrir diretamente se iframe bloqueado
  // (abre nova aba se modal não carregar)
  iframe.addEventListener('error', () => {
    window.open(IFRAME_PATH, "_blank", "noopener");
    closeModal();
  });
</script>

</body>
</html>
