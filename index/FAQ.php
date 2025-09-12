<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FOAG — FAQ</title>
  <meta name="description" content="Perguntas frequentes sobre o FOAG: contas, recursos, revisão espaçada, privacidade e mais." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    :root{ --brand-500:#38a5ff; --brand-600:#2c7fd6; --brand-700:#1f5ea3; --bg:#ffffff; --surface:#f9f9f9; --text:#222; --muted:#555; }
    body.dark{ --bg:#0d1117; --surface:#161b22; --text:#e6edf3; --muted:#a5b4c3; }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{ margin:0; font-family:Poppins, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji"; color:var(--text); background:var(--bg); transition:.3s background,.3s color; padding-top:var(--header-h,60px); }

    /* Fade-out para transições de página */
    body.fade-out{opacity:0; transform:translateY(10px); transition:opacity .35s ease, transform .35s ease;}

    /* Header com auto-hide */
    header.cabecalho{position:fixed; top:0; left:0; z-index:1000; width:100%; background:var(--brand-500); color:#fff; border-bottom:1px solid rgba(0,0,0,.1); transition:transform .3s ease, box-shadow .3s ease;}
    header.cabecalho.hide{transform:translateY(-100%);} 
    header.cabecalho.elev{box-shadow:0 8px 20px rgba(0,0,0,.08)}
    .nav{max-width:1152px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; gap:18px;}
    .logo{font-family:"Snap ITC", Poppins, sans-serif; font-size:32px; line-height:1; display:flex; align-items:center; gap:10px; color:#fff;}
    .nav a{color:#fff; text-decoration:none; font-weight:500}
    .nav a:hover{opacity:.85}
    .nav-links{display:flex; gap:22px; margin-left:auto}
    .btn{display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.15); color:#fff; text-decoration:none; font-weight:600}
    .btn.primary{background:#fff; color:var(--brand-600)}
    #themeToggle{font-size:22px; margin-left:12px; cursor:pointer}

    /* Container principal */
    main{ max-width:1000px; margin:0 auto; padding:32px 20px 64px; }
    .title{ font-size:clamp(28px, 4vw, 42px); font-weight:800; margin:10px 0 8px; color:var(--brand-700); }
    .sub{ color:var(--muted); margin:0 0 18px; }

    /* Busca */
    .search{ display:flex; gap:10px; align-items:center; background:var(--surface); border:1px solid #ddd; border-radius:14px; padding:10px 12px; }
    .search input{ flex:1; border:0; outline:0; background:transparent; color:var(--text); font-size:16px; }

    /* Lista de FAQs (accordion) */
    .faq-list{ margin-top:18px; display:grid; gap:12px; }
    .faq{ background:var(--surface); border:1px solid #ddd; border-radius:14px; overflow:hidden; }
    .faq summary{ list-style:none; cursor:pointer; padding:16px 18px; font-weight:600; display:flex; align-items:center; gap:10px; }
    .faq summary::-webkit-details-marker{ display:none; }
    .faq[open] summary{ border-bottom:1px solid #e6e6e6; }
    .faq .content{ padding:16px 18px; color:var(--muted); }
    .faq i.chev{ margin-left:auto; transition: transform .25s ease; }
    .faq[open] i.chev{ transform:rotate(180deg); }

    .hint{ font-size:13px; color:var(--muted); margin-top:6px }

    footer{border-top:1px solid #eee; color:var(--muted); padding:26px 20px}
    .foot{max-width:1152px; margin:0 auto; display:flex; gap:14px; justify-content:space-between; align-items:center}
    .foot a{color:var(--muted); text-decoration:none}

    @media (max-width:680px){ .nav-links{display:none} }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="cabecalho" id="header">
    <nav class="nav">
      <div class="logo">FOAG</div>
      <div class="nav-links">
        <a href="index.php" id="goHome">Início</a>
        <a href="recursos.php">Recursos</a>
        <a href="como.php">Como funciona</a>
      </div>
      <a id="startBtn" class="btn primary" href="#"><i class="fa-solid fa-rocket"></i> Começar</a>
      <i id="themeToggle" class="fa-solid fa-moon"></i>
    </nav>
  </header>

  <main>
    <h1 class="title">Perguntas frequentes</h1>
    <p class="sub">Tire dúvidas rápidas sobre como usar o FOAG. Se não encontrar sua resposta, fale com a gente no <a href="#contato">Contato</a>.</p>

    <div class="search" role="search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input id="faqSearch" type="search" placeholder="Buscar por palavra-chave (ex.: revisão, exportar, senha)" aria-label="Buscar no FAQ">
    </div>

    <div class="faq-list" id="faqList">
      <details class="faq" open>
        <summary><i class="fa-solid fa-layer-group"></i> O FOAG é gratuito? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Sim! Você pode usar grátis com os recursos essenciais. Recursos avançados podem exigir upgrade no futuro.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-user-plus"></i> Como crio minha conta? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Clique em <strong>Começar</strong> no topo ou <strong>Criar conta</strong> na home. Você será direcionada para <code>login/login.php</code>, onde pode criar sua conta ou entrar.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-rotate"></i> Como funciona a revisão espaçada? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">O FOAG sugere um ciclo simples (1-3-7-15 dias) para revisar tópicos estudados. Você recebe lembretes na aba de revisões e pode ajustar as datas conforme sua rotina.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-clock"></i> Tem timer de foco (pomodoro)? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Sim. O timer conta sessões de foco e registra automaticamente no seu histórico, ajudando a bater a meta semanal de horas.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-chart-line"></i> Consigo ver gráficos e relatórios? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Você acompanha progresso por semana, disciplina e objetivo. Em breve, adicionaremos exportação de relatórios em CSV/PDF.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-shield-halved"></i> Meus dados são privados? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Sim. Não exibimos anúncios e evitamos rastreadores invasivos. Você tem controle sobre exportar e excluir seus dados.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-lock"></i> Esqueci minha senha, e agora? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Na página <code>login/login.php</code>, clique em <em>Esqueci minha senha</em> para receber um link de redefinição no e‑mail cadastrado.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-mobile-screen-button"></i> Tem app para celular? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">O FOAG é <em>PWA</em>: você pode instalar a versão web no celular (Android/Chrome e iOS/Safari). App nativo está nos planos.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-people-group"></i> Posso usar com meus alunos/turma? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Sim. Estamos preparando um modo <strong>Equipe</strong> para cursos/estúdios com painel do professor e relatórios por aluno.</div>
      </details>

      <details class="faq">
        <summary><i class="fa-solid fa-headset"></i> Como falo com o suporte? <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i></summary>
        <div class="content">Use o link <strong>Contato</strong> no rodapé ou envie e‑mail para <a href="mailto:support@foag.app">support@foag.app</a>.</div>
      </details>
    </div>

    <p class="hint">Dica: use a busca acima para filtrar perguntas rapidamente.</p>
  </main>

  <footer>
    <div class="foot">
      <div>© <span id="year"></span> FOAG. Feito com foco e carinho.</div>
      <div style="display:flex; gap:14px">
        <a href="index.html" id="footHome">Início</a>
        <a href="#contato">Contato</a>
        <a href="#politica">Privacidade</a>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();

    // Dark mode toggle
    const toggle = document.getElementById('themeToggle');
    toggle.addEventListener('click', ()=>{ document.body.classList.toggle('dark'); toggle.classList.toggle('fa-moon'); toggle.classList.toggle('fa-sun'); });

    // Auto-hide header
    let lastScroll = 0; const header = document.getElementById('header');
    window.addEventListener('scroll', ()=>{
      const y = window.pageYOffset; header.classList.toggle('elev', y>2);
      if(y > lastScroll && y > 80){ header.classList.add('hide'); } else { header.classList.remove('hide'); }
      lastScroll = y;
    });

    // Ajusta padding-top
    function syncHeaderSpace(){ const h = header?.offsetHeight || 60; document.documentElement.style.setProperty('--header-h', h + 'px'); }
    window.addEventListener('load', syncHeaderSpace); window.addEventListener('resize', syncHeaderSpace); setTimeout(syncHeaderSpace, 300); setTimeout(syncHeaderSpace, 1000);

    // Redirecionamento com fade-out para login
    function goLogin(e){ e.preventDefault(); document.body.classList.add('fade-out'); setTimeout(()=>{ window.location.href = 'login/login.php'; }, 350); }
    document.getElementById('startBtn').addEventListener('click', goLogin);

    // Links que vão pra home com fade-out (se for outra página)
    function goWithFade(url){ document.body.classList.add('fade-out'); setTimeout(()=>{ window.location.href = url; }, 250); }
    const goHome = document.getElementById('goHome'); if(goHome){ goHome.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(goHome.getAttribute('href')||'index.html'); }); }
    const footHome = document.getElementById('footHome'); if(footHome){ footHome.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(footHome.getAttribute('href')||'index.html'); }); }

    // Busca no FAQ
    const input = document.getElementById('faqSearch');
    const faqs = Array.from(document.querySelectorAll('.faq'));
    input.addEventListener('input', ()=>{
      const q = input.value.trim().toLowerCase();
      faqs.forEach(f=>{
        const txt = f.innerText.toLowerCase();
        const match = txt.includes(q);
        f.style.display = match ? '' : 'none';
        if(match && q && !f.open){ f.open = true; }
      });
    });
  </script>
</body>
</html>
