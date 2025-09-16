<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FOAG — Como funciona</title>
  <meta name="description" content="Como o FOAG funciona: crie metas, estude com foco e faça revisões espaçadas. Passo a passo simples, em poucos minutos." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    :root{ --brand-500:#38a5ff; --brand-600:#2c7fd6; --brand-700:#1f5ea3; --bg:#ffffff; --surface:#f9f9f9; --text:#222; --muted:#555; }
    body.dark{ --bg:#0d1117; --surface:#161b22; --text:#e6edf3; --muted:#a5b4c3; }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{ margin:0; font-family:Poppins, system-ui, -apple-system, Segoe UI, Roboto, Arial; color:var(--text); background:var(--bg); transition:.3s background,.3s color; padding-top:var(--header-h,60px); }

    /* Fade-out para transições */
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

    main{ max-width:1152px; margin:0 auto; padding:32px 20px 72px; }

    .title{ font-size:clamp(28px, 4vw, 42px); font-weight:800; margin:10px 0 8px; color:var(--brand-700); }
    .sub{ color:var(--muted); margin:0 0 22px; }

    /* Stepper */
    .steps{ display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin:22px 0 34px; }
    .step{ background:var(--surface); border:1px solid #ddd; border-radius:16px; padding:18px; box-shadow:0 4px 10px rgba(0,0,0,.05); }
    .step .badge{ display:inline-flex; align-items:center; gap:8px; font-weight:700; color:var(--brand-700); }
    .step .badge .dot{ width:8px; height:8px; border-radius:999px; background:var(--brand-500); box-shadow:0 0 12px rgba(56,165,255,.45); }
    .step h3{ margin:10px 0 6px; font-size:18px; }
    .step p{ color:var(--muted); margin:0; }

    /* Demo / vídeo */
    .demo{ display:grid; grid-template-columns:1.05fr .95fr; gap:22px; margin:14px 0 40px; align-items:center; }
    .mock{ background:var(--surface); border:1px solid #ddd; border-radius:18px; padding:16px; box-shadow:0 14px 40px -18px rgba(56,165,255,.35); }
    .mock .bar{ display:flex; gap:6px; padding:8px; background:var(--surface) }
    .mock .dot{ width:9px; height:9px; border-radius:50%; background:#c8d3e1 }
    .mock .screen{ padding:14px; background:var(--bg) }
    .mock .pill{ display:inline-flex; align-items:center; gap:8px; padding:7px 10px; border-radius:999px; background:#e6f2ff; border:1px solid #cfe6ff; margin:6px 8px 0 0; font-size:13px }
    .video{ position:relative; background:var(--surface); border:1px solid #ddd; border-radius:18px; padding:0; height:260px; display:grid; place-items:center; overflow:hidden }
    .video .play{ font-size:42px; color:var(--brand-600); }
    .video small{ color:var(--muted) }

    /* Checklist 5 minutos */
    .checklist{ background:var(--surface); border:1px solid #ddd; border-radius:18px; padding:18px; }
    .checklist h3{ margin:0 0 8px; }
    .checks{ display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .checks label{ display:flex; gap:10px; align-items:flex-start; }
    .checks input{ margin-top:4px }

    /* CTA final */
    .cta{ margin-top:34px; display:flex; gap:12px; flex-wrap:wrap }

    footer{border-top:1px solid #eee; color:var(--muted); padding:26px 20px}
    .foot{max-width:1152px; margin:0 auto; display:flex; gap:14px; justify-content:space-between; align-items:center}
    .foot a{color:var(--muted); text-decoration:none}

    @media (max-width:960px){ .steps{grid-template-columns:1fr 1fr} .demo{grid-template-columns:1fr} }
    @media (max-width:640px){ .steps{grid-template-columns:1fr} .checks{grid-template-columns:1fr} .nav-links{display:none} }
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
        <a href="faq.html" id="goFaq">FAQ</a>
      </div>
      <a id="startBtn" class="btn primary" href="#"><i class="fa-solid fa-rocket"></i> Começar</a>
      <i id="themeToggle" class="fa-solid fa-moon"></i>
    </nav>
  </header>

  <main>
    <h1 class="title">Como funciona</h1>
    <p class="sub">O FOAG não é só timer 😉 — ele organiza <strong>horários</strong>, marca <strong>presenças/faltas</strong>, guarda <strong>anotações</strong> e calcula <strong>notas/médias</strong> do seu boletim. Veja o fluxo:</p>

    <!-- Passos -->
    <section class="steps">
      <article class="step">
        <div class="badge"><span class="dot"></span> Passo 1</div>
        <h3><i class="fa-solid fa-calendar-days"></i> Monte horários e turmas</h3>
        <p>Cadastre disciplinas, turmas e seus horários da semana. Assim você enxerga sua rotina e evita choques.</p>
      </article>
      <article class="step">
        <div class="badge"><span class="dot"></span> Passo 2</div>
        <h3><i class="fa-solid fa-user-check"></i> Marque presença e faltas</h3>
        <p>Registre presenças/faltas em cada aula e acompanhe limites por matéria com alertas antecipados.</p>
      </article>
      <article class="step">
        <div class="badge"><span class="dot"></span> Passo 3</div>
        <h3><i class="fa-solid fa-clipboard"></i> Anote conteúdos e calcule o boletim</h3>
        <p>Crie anotações rápidas por aula/tópico e lance notas, pesos e avaliações para ver média parcial e final automaticamente.</p>
      </article>
    </section>

    <!-- Demo -->
    <section class="demo">
      <div class="mock" aria-label="Prévia do painel FOAG">
        <div class="bar"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
        <div class="screen">
          <div class="pill"><i class="fa-solid fa-user-check"></i> Faltas no mês: 2</div>
          <div class="pill"><i class="fa-solid fa-square-poll-vertical"></i> Média Geral: 8,2</div>
          <div class="pill"><i class="fa-solid fa-calendar-day"></i> Aulas hoje: 3</div>
        </div>
      </div>
      <div class="video" aria-label="Vídeo rápido de como usar">
        <div>
          <div class="play"><i class="fa-solid fa-circle-play"></i></div>
          <small>Dica: troque por um vídeo de 30s mostrando horários, faltas, notas e anotações.</small>
        </div>
      </div>
    </section>

    <!-- Checklist 5 minutos -->
    <section class="checklist">
      <h3><i class="fa-solid fa-list-check"></i> Comece em 5 minutos</h3>
      <div class="checks">
        <label><input type="checkbox"> Criar conta em <code>login/login.php</code></label>
        <label><input type="checkbox"> Adicionar disciplinas/turmas principais</label>
        <label><input type="checkbox"> Definir e salvar seus <strong>horários</strong> da semana</label>
        <label><input type="checkbox"> Marcar presença/falta na primeira aula</label>
        <label><input type="checkbox"> Criar uma <strong>anotação</strong> rápida do conteúdo</label>
        <label><input type="checkbox"> Lançar uma <strong>nota</strong> com peso e conferir a média</label>
      </div>
      <div class="cta">
        <a class="btn primary" id="startBtn2" href="#"><i class="fa-solid fa-rocket"></i> Começar agora</a>
        <a class="btn" id="goFaq2" href="faq.html"><i class="fa-solid fa-circle-question"></i> Ver FAQ</a>
      </div>
    </section>
  </main>

  <footer>
    <div class="foot">
      <div>© <span id="year"></span> FOAG. Feito com foco e carinho.</div>
      <div style="display:flex; gap:14px">
        <a href="index.html" id="footHome">Início</a>
        <a href="faq.html" id="footFaq">FAQ</a>
        <a href="#contato">Contato</a>
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

    // Fade-out + redirecionamentos
    function goWithFade(url, delay=300){ document.body.classList.add('fade-out'); setTimeout(()=>{ window.location.href = url; }, delay); }
    function goLogin(e){ e.preventDefault(); goWithFade('login/login.php', 350); }

    document.getElementById('startBtn').addEventListener('click', goLogin);
    document.getElementById('startBtn2').addEventListener('click', goLogin);

    // navegação com fade
    const goHome = document.getElementById('goHome'); if(goHome){ goHome.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(goHome.getAttribute('href')||'index.html', 250); }); }
    const footHome = document.getElementById('footHome'); if(footHome){ footHome.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(footHome.getAttribute('href')||'index.html', 250); }); }
    const goFaq = document.getElementById('goFaq'); if(goFaq){ goFaq.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(goFaq.getAttribute('href')||'faq.html', 250); }); }
    const goFaq2 = document.getElementById('goFaq2'); if(goFaq2){ goFaq2.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(goFaq2.getAttribute('href')||'faq.html', 250); }); }
    const footFaq = document.getElementById('footFaq'); if(footFaq){ footFaq.addEventListener('click', (e)=>{ e.preventDefault(); goWithFade(footFaq.getAttribute('href')||'faq.html', 250); }); }
  </script>
</body>
</html>
