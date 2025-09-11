<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FOAG — Estude melhor, com foco</title>
  <meta name="description" content="FOAG é uma plataforma simples para organizar estudos: metas, ciclos de revisão e gráficos que mostram sua evolução." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    :root{
      --brand-500:#38a5ff; --brand-600:#2c7fd6; --brand-700:#1f5ea3;
      --bg:#ffffff; --surface:#f9f9f9; --text:#222; --muted:#555;
    }
    body.dark{ --bg:#0d1117; --surface:#161b22; --text:#e6edf3; --muted:#a5b4c3; }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{margin:0; font-family:Poppins, sans-serif; color:var(--text); background:var(--bg); transition:.3s background,.3s color; padding-top:var(--header-h,60px);}

    /* animação de saída */
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

    /* Home */
    .hero{max-width:1152px; margin:0 auto; padding:56px 20px 24px; display:grid; grid-template-columns:1.15fr .85fr; gap:36px; align-items:center}
    .kicker{display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px; background:rgba(56,165,255,.12); border:1px solid rgba(56,165,255,.25); color:var(--brand-600); font-weight:600; font-size:13px}
    .title{font-size:clamp(30px, 4.2vw, 56px); line-height:1.05; margin:.4rem 0 1rem; font-weight:800}
    .subtitle{color:var(--muted); font-size:clamp(15px,1.6vw,18px); max-width:56ch}

    .cta-row{display:flex; gap:12px; margin-top:22px; flex-wrap:wrap}
    .trust{display:flex; gap:14px; align-items:center; margin-top:22px; color:var(--muted); font-size:14px}

    .hero-card{position:relative; border-radius:20px; padding:18px; background:var(--surface); border:1px solid #ddd; box-shadow:0 12px 40px -18px rgba(56,165,255,.35);}
    .sample{border-radius:14px; overflow:hidden; border:1px solid #ddd;}
    .sample .bar{display:flex; gap:6px; padding:10px; background:var(--surface)}
    .sample .dot{width:10px; height:10px; border-radius:50%; background:#bbb}
    .sample .screen{padding:16px; background:var(--bg)}
    .sample .pill{display:inline-flex; align-items:center; gap:8px; padding:7px 10px; border-radius:999px; background:#e6f2ff; border:1px solid #cfe6ff; margin:6px 8px 0 0; font-size:13px}

    section{max-width:1152px; margin:0 auto; padding:64px 20px}
    .section-title{font-size:28px; margin:0 0 10px; font-weight:800; color:var(--brand-700)}
    .section-sub{color:var(--muted); margin:0 0 28px}

    .grid{display:grid; grid-template-columns:repeat(3,1fr); gap:18px}
    .card{background:var(--surface); border:1px solid #ddd; border-radius:18px; padding:18px; box-shadow:0 4px 8px rgba(0,0,0,0.05)}
    .icon{font-size:22px; width:40px; height:40px; display:inline-grid; place-items:center; border-radius:12px; background:#e6f2ff; color:var(--brand-600); margin-bottom:8px}
    .card h3{margin:6px 0 6px; font-size:18px}
    .card p{color:var(--muted); font-size:15px}

    footer{border-top:1px solid #eee; color:var(--muted); padding:26px 20px}
    .foot{max-width:1152px; margin:0 auto; display:flex; gap:14px; justify-content:space-between; align-items:center}
    .foot a{color:var(--muted); text-decoration:none}

    @media (max-width:960px){.hero{grid-template-columns:1fr; padding-top:28px}.grid{grid-template-columns:1fr 1fr}.nav-links{display:none}}
    @media (max-width:680px){.grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <!-- Header -->
  <header class="cabecalho" id="header">
    <nav class="nav">
      <div class="logo">FOAG</div>
      <div class="nav-links">
        <a href="#features">Recursos</a>
        <a href="#como-funciona">Como funciona</a>
        <a href="#faq">FAQ</a>
      </div>
      <a id="startBtn" class="btn primary" href="#"><i class="fa-solid fa-rocket"></i> Começar</a>
      <i id="themeToggle" class="fa-solid fa-moon"></i>
    </nav>
  </header>

  <!-- HOME -->
  <main id="home">
    <section class="hero">
      <div>
        <span class="kicker"><i class="fa-solid fa-bolt"></i> Organização sem fricção</span>
        <h1 class="title">Estudos organizados, mente leve — com o FOAG</h1>
        <p class="subtitle">Defina metas semanais, acompanhe horas de estudo e receba lembretes de revisão espaçada. Tudo em um painel simples e bonito.</p>
        <div class="cta-row">
          <a id="startBtn2" class="btn primary" href="#"><i class="fa-solid fa-user-plus"></i> Criar conta grátis</a>
        </div>
        <div class="trust">
          <i class="fa-solid fa-shield-check"></i> Sem anúncios • Seus dados permanecem seus
        </div>
      </div>

      <aside class="hero-card">
        <div class="sample">
          <div class="bar"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
          <div class="screen">
            <div class="pill"><i class="fa-solid fa-bullseye"></i> Meta semanal: 10h</div>
            <div class="pill"><i class="fa-solid fa-rotate"></i> Revisões hoje: 3</div>
            <div class="pill"><i class="fa-solid fa-chart-line"></i> Progresso: 68%</div>
          </div>
        </div>
      </aside>
    </section>

    <section id="features">
      <h2 class="section-title">Recursos que importam</h2>
      <p class="section-sub">Sem complicação: só o que você precisa para estudar melhor.</p>
      <div class="grid">
        <article class="card"><div class="icon"><i class="fa-solid fa-layer-group"></i></div><h3>Metas por matéria</h3><p>Defina horas e tópicos por disciplina. O FOAG distribui automaticamente na sua semana.</p></article>
        <article class="card"><div class="icon"><i class="fa-solid fa-clock"></i></div><h3>Timer focado</h3><p>Sessions de foco com pausa inteligente e contagem automática para seu histórico.</p></article>
        <article class="card"><div class="icon"><i class="fa-solid fa-rotate"></i></div><h3>Revisão espaçada</h3><p>Algoritmo simples para lembrar você na hora certa e consolidar o aprendizado.</p></article>
      </div>
    </section>
  </main>

  <footer>
    <div class="foot">
      <div>© <span id="year"></span> FOAG. Feito com foco e carinho.</div>
      <div style="display:flex; gap:14px">
        <a href="#politica">Privacidade</a>
        <a href="#termos">Termos</a>
        <a href="#contato" id="contato">Contato</a>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();

    // Dark mode toggle
    const toggle = document.getElementById('themeToggle');
    toggle.addEventListener('click', ()=>{
      document.body.classList.toggle('dark');
      toggle.classList.toggle('fa-moon');
      toggle.classList.toggle('fa-sun');
    });

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

    // Redirecionamento com animação
    function goLogin(e){
      e.preventDefault();
      document.body.classList.add('fade-out');
      setTimeout(()=>{ window.location.href = 'login/login.php'; }, 350);
    }

    document.getElementById('startBtn').addEventListener('click', goLogin);
    document.getElementById('startBtn2').addEventListener('click', goLogin);
  </script>
</body>
</html>
