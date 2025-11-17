<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('SERVER_MESSAGE') }}</title>
  <link href="https://fonts.bunny.net/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#070810;
      --fg:#E9ECF1;
      --muted:#9BA3AE;
      --border:rgba(255,255,255,.12);
      --glass:rgba(255,255,255,.06);
      --brand1:#8b5cf6; /* violet */
      --brand2:#22d3ee; /* cyan */
      --glow: 0 10px 30px rgba(56,189,248,.15), 0 20px 60px rgba(139,92,246,.18);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; color:var(--fg);
      font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
      background:
        radial-gradient(60rem 40rem at 15% -10%, rgba(139,92,246,.16), transparent 55%),
        radial-gradient(60rem 40rem at 110% 10%, rgba(34,211,238,.16), transparent 55%),
        linear-gradient(180deg,#070810,#0a0e1a 30%, #070810);
    }

    /* NAV — login/register conservé */
    .topbar{
      position:fixed; top:0; left:0; right:0; height:60px; z-index:20;
      display:flex; align-items:center; justify-content:flex-end; gap:18px;
      padding:0 24px; backdrop-filter:saturate(140%) blur(10px);
      background:linear-gradient(180deg, var(--glass), transparent);
      border-bottom:1px solid var(--border);
    }
    .topbar a{color:#d9d9e0; text-decoration:none; font-size:.9rem}
    .topbar a:hover{color:#fff; text-decoration:underline}

    /* HERO — titre à gauche, centré verticalement */
    .hero{
      margin-top:60px; min-height:64vh; position:relative; overflow:hidden;
      border-bottom:1px solid var(--border);
      box-shadow:var(--glow);
    }
    .hero__bg{
      position:absolute; inset:0;
      background: url('{{ asset('images/banniere.png') }}') center/cover no-repeat;
      filter: saturate(1.05) contrast(1.05);
    }
    /* voile pour lisibilité + rubans aurora */
    .hero::before{
      content:""; position:absolute; inset:0;
      background:
        radial-gradient(60rem 30rem at 0% 50%, rgba(7,8,16,.85), transparent 60%),
        linear-gradient(90deg, rgba(7,8,16,.75), rgba(7,8,16,.35) 40%, rgba(7,8,16,.0));
    }
    .hero::after{
      content:""; position:absolute; right:-10%; top:-30%; width:70%; height:160%;
      background: conic-gradient(from 180deg at 50% 50%, rgba(139,92,246,.35), rgba(34,211,238,.35), transparent 60%);
      filter: blur(70px); opacity:.45; pointer-events:none;
    }
    .hero__inner{
      position:relative; z-index:1; height:64vh; min-height:420px;
      display:flex; align-items:center;
      padding: 0 clamp(24px, 8vw, 120px);
    }
    .kicker{
      display:inline-flex; gap:.5rem; align-items:center;
      padding:.35rem .6rem; border:1px solid var(--border);
      border-radius:999px; color:var(--muted); font-size:.8rem;
      background:rgba(255,255,255,.06)
    }
    .title{
      margin:.75rem 0 .25rem; letter-spacing:-.02em;
      font-weight:800; font-size:clamp(2rem, 5.2vw, 3.4rem);
      line-height:1.08; color:#fff;
    }
    .subtitle{color:var(--muted); font-size:1rem}

    /* SECTION ÉQUIPE — timeline élégante */
    .section{
      max-width:1100px; margin:0 auto; padding:56px 20px 96px;
    }
    .section__head{
      display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;
    }
    .section__title{
      margin:0; font-size:clamp(1.6rem,3.8vw,2.4rem); font-weight:800;
      background:linear-gradient(90deg, var(--brand1), var(--brand2));
      -webkit-background-clip:text; background-clip:text; color:transparent;
    }

    .timeline{
      position:relative; margin-top:32px;
    }
    .timeline::before{
      content:""; position:absolute; left:50%; top:0; bottom:0; width:2px;
      background:linear-gradient(180deg, rgba(139,92,246,.45), rgba(34,211,238,.45));
      transform:translateX(-50%); opacity:.7;
    }
    .titem{
      position:relative; display:grid; grid-template-columns:1fr 1fr; gap:28px; margin:40px 0;
    }
    .titem__card{
      border:1px solid var(--border); border-radius:1rem; overflow:hidden;
      background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
      backdrop-filter:saturate(140%) blur(8px);
      padding:16px 18px; box-shadow:var(--glow);
      transition:transform .15s ease, box-shadow .25s ease, border-color .2s ease;
    }
    .titem__card:hover{
      transform:translateY(-2px);
      box-shadow:0 18px 40px rgba(0,0,0,.25), var(--glow);
      border-color:rgba(34,211,238,.35);
    }
    .titem__meta{
      display:flex; align-items:center; gap:12px; margin-bottom:6px;
    }
    .avatar{
      width:56px; height:56px; border-radius:50%; overflow:hidden; flex:0 0 auto;
      border:1px solid var(--border); background:#fff;
    }
    .avatar img{width:100%; height:100%; object-fit:cover}
    .name{margin:0; font-weight:700; font-size:1.05rem}
    .desc{margin:.2rem 0 0; color:var(--muted); line-height:1.6}

    /* connecteur rond au centre */
    .dot{
      position:absolute; left:50%; top:50%; transform:translate(-50%,-50%);
      width:14px; height:14px; border-radius:50%;
      background:linear-gradient(90deg, var(--brand1), var(--brand2));
      box-shadow:0 0 0 6px rgba(139,92,246,.15), 0 0 0 12px rgba(34,211,238,.1);
    }

    /* alternance gauche/droite */
    .titem:nth-child(odd) .titem__card{grid-column:1/2}
    .titem:nth-child(odd) .titem__spacer{grid-column:2/3}
    .titem:nth-child(even) .titem__spacer{grid-column:1/2}
    .titem:nth-child(even) .titem__card{grid-column:2/3}

    /* mobile */
    @media (max-width:900px){
      .timeline::before{left:28px}
      .titem{grid-template-columns: 56px 1fr; gap:16px}
      .titem .dot{left:28px}
      .titem__spacer{display:none}
      .titem__card{grid-column:2/3}
    }

    /* Footer versions */
    .versions{
      max-width:1100px; margin:0 auto; padding:0 20px 42px;
      display:flex; justify-content:space-between; align-items:center;
      color:#A7AFB8; font-size:.9rem;
    }
  </style>
</head>
<body>

  {{-- NAV : Login / Register --}}
  @if (Route::has('login'))
  <div class="topbar">
    @auth
      <a href="{{ url('/dashboard') }}">Dashboard</a>
    @else
      <a href="{{ route('login') }}">Login</a>
      @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
      @endif
    @endauth
  </div>
  @endif

  {{-- HERO --}}
  <header class="hero">
    <div class="hero__bg" aria-hidden="true"></div>
    <div class="hero__inner">
      <div>
        <span class="kicker">TP FINAL DOCKER</span>
        @if (env('SERVER_MESSAGE'))
        <h1>{{ env('SERVER_MESSAGE') }}</h1>
        @endif
      </div>
    </div>
  </header>

  {{-- TEAM — Timeline --}}
  <section class="section">
    <div class="section__head">
      <h2 class="section__title">L'équipe</h2>
    </div>

    <div class="timeline">

      {{-- 1 --}}
      <div class="titem">
        <div class="titem__card">
          <div class="titem__meta">
            <figure class="avatar"><img src="{{ asset('images/bilal.png') }}" alt="Bilal Taoufik"></figure>
            <div>
              <h3 class="name">Bilal Taoufik</h3>
              <small style="color:var(--muted)">Docker-compose & Blade</small>
            </div>
          </div>
          <p class="desc">Mise en place du Docker-compose + Dockerfile</p>
        </div>
        <div class="titem__spacer"></div>
        <span class="dot" aria-hidden="true"></span>
      </div>

      {{-- 2 --}}
      <div class="titem">
        <div class="titem__spacer"></div>
        <div class="titem__card">
          <div class="titem__meta">
            <figure class="avatar"><img src="{{ asset('images/matheo.png') }}" alt="Matheo"></figure>
            <div>
              <h3 class="name">Matheo</h3>
              <small style="color:var(--muted)">Dockerfile & scripts .sh</small>
            </div>
          </div>
          <p class="desc">Mise en place des nginx</p>
        </div>
        <span class="dot" aria-hidden="true"></span>
      </div>

      {{-- 3 --}}
      <div class="titem">
        <div class="titem__card">
          <div class="titem__meta">
            <figure class="avatar"><img src="{{ asset('images/zak.jpg') }}" alt="Zakaraia"></figure>
            <div>
              <h3 class="name">Zakaria</h3>
              <small style="color:var(--muted)">Nginx & .env</small>
            </div>
          </div>
          <p class="desc">Mise en place de l'automatisation</p>
        </div>
        <div class="titem__spacer"></div>
        <span class="dot" aria-hidden="true"></span>
      </div>

      {{-- 4 --}}
      <div class="titem">
        <div class="titem__spacer"></div>
        <div class="titem__card">
          <div class="titem__meta">
            <figure class="avatar"><img src="{{ asset('images/ia.jpg') }}" alt="IA"></figure>
            <div>
              <h3 class="name">IA</h3>
              <small style="color:var(--muted)">Support</small>
            </div>
          </div>
          <p class="desc">J'ai tout fais finalement...</p>
        </div>
        <span class="dot" aria-hidden="true"></span>
      </div>

    </div>
  </section>

  {{-- Versions --}}
  <footer class="versions">
    <span>&copy; {{ date('Y') }} • TP FINAL DOCKER</span>
    <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} • PHP v{{ PHP_VERSION }}</span>
  </footer>
</body>
</html>
