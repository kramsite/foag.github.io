<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FOAG — Início</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand1: #38a5ff;
      --fg: #ffffff;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      font-family: 'Poppins', sans-serif;
      color: var(--fg);
      background: #38a5ff;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .container {
      position: relative;
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 5%;
    }

    /* LOGO + GLOW (1) */
    .logo {
      position: relative;
      font-family: 'Snap ITC', sans-serif;
      font-size: clamp(120px, 40vw, 180px);
      font-weight: bold;
      color: var(--fg);
      text-shadow: 0 6px 18px rgba(0,0,0,0.25);
      z-index: 2;
    }
    .logo::after{
      content:"";
      position:absolute; inset:-15% -10% -15% -10%;
      background: radial-gradient(40% 40% at 50% 50%, rgba(255,255,255,.35) 0%, rgba(56,165,255,.28) 35%, rgba(0,0,0,0) 65%);
      filter: blur(12px);
      z-index:-1;
      animation: glowPulse 6s ease-in-out infinite;
    }

    @keyframes glowPulse {
      0%,100% { opacity: .55; transform: scale(1); }
      50%     { opacity: .9;  transform: scale(1.06); }
    }

    /* TRIÂNGULO + ANIMAÇÃO DE ENTRADA (8) + SOMBRA AMBIENTE (9) */
    .triangle {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translate(0, -50%);
      width: 55%;
      height: 230%;
      background: #ffffff;
      clip-path: polygon(100% 0, 0 50%, 100% 100%);
      z-index: 1;
      box-shadow: 0 24px 70px rgba(0,0,0,0.28), 0 8px 24px rgba(0,0,0,0.14);
      animation: slideInTri .46s cubic-bezier(.2,.65,.2,1) both;
      transform-origin: right center;
    }

    @keyframes slideInTri {
      from { transform: translate(20%, -50%); opacity: .0; }
      to   { transform: translate(0,   -50%); opacity: 1; }
    }



    /* TIPOGRAFIA (6) */
     h1 {
      margin: 0 0 6px 0;
      font-size: clamp(30px, 3.8vw, 36px);
      line-height: 1.18;
      font-weight: 600; /* Poppins semi-bold */
    }
    h1 .foag {
      font-family: 'Snap ITC', sans-serif;
      color: #38a5ff;
      font-size: 1.2em; /* maior para contraste */
      letter-spacing: .02em;
      display: inline-block;
      transform: translateY(1px);
    }

    /* BOTÃO + MICROINTERAÇÃO (3) */
    .btn {
      display: inline-flex; align-items: center; gap: 10px;
      margin-top: 14px;
      background-color: var(--brand1);
      color: #ffffff;
      border: none;
      border-radius: 26px;
      padding: 20px 30px;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.3s, background .2s;
      box-shadow: 0 6px 16px rgba(0,0,0,0.22);
    }
    .btn .arrow{ display:inline-block; transition: transform .18s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(0,0,0,0.28); }
    .btn:hover .arrow{ transform: translateX(6px); }

    /* ACESSIBILIDADE */
    .btn:focus-visible { outline: 3px solid #0b3b8f; outline-offset: 3px; }

    /* REDUZIR MOVIMENTO SE PREFERIDO */
    @media (prefers-reduced-motion: reduce){
      .logo::after, .triangle{ animation: none !important; }
      .btn, .btn .arrow { transition: none !important; }
    }

    /* RESPONSIVO */
    @media (max-width: 900px) {
      .triangle {
        width: 100%; height: 100%; top: 0; transform: none; animation: none;
        clip-path: polygon(100% 0, 0 100%, 100% 100%);
      }
      .content { position: relative; right: 0; transform: none; color: var(--brand2); margin: 40px auto 0; }
      .logo { text-align: center; margin: 32px auto 0; font-size: clamp(80px, 24vw, 140px); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">FOAG</div>

    <div class="triangle"></div>
    <div class="content">
      <div class="glass">
        <h1>Organize seus estudos com o <span class="foag">FOAG</span></h1>
        <button class="btn" onclick="window.location.href='../login/login.php'">
          Começar <span class="arrow">→</span>
        </button>
      </div>
    </div>
  </div>
</body>
</html>
