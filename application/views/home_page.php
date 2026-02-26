<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('includes/title.php'); ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/images/Attendance.png" />
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Sora', sans-serif;
      background: #f0f4ff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 15% 20%, #dce8ff 0%, transparent 55%),
        radial-gradient(ellipse 70% 50% at 85% 80%, #e4edff 0%, transparent 55%),
        #f0f4ff;
      z-index: 0;
      animation: meshShift 12s ease-in-out infinite alternate;
    }

    @keyframes meshShift {
      from {
        filter: hue-rotate(0deg);
      }

      to {
        filter: hue-rotate(8deg);
      }
    }

    .blob {
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
      z-index: 0;
      animation: blobFloat var(--d, 10s) ease-in-out infinite alternate;
    }

    .blob-a {
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, #c7d9ff55, #a8c4ff22);
      top: -200px;
      left: -150px;
      --d: 11s;
      filter: blur(60px);
    }

    .blob-b {
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, #dde8ff44, #c0d6ff11);
      bottom: -150px;
      right: -100px;
      --d: 14s;
      filter: blur(50px);
    }

    @keyframes blobFloat {
      from {
        transform: translate(0, 0) scale(1);
      }

      to {
        transform: translate(40px, 30px) scale(1.1);
      }
    }

    /* ── card ── */
    .card {
      position: relative;
      z-index: 1;
      width: min(960px, 100%);
      min-height: 560px;
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(32px) saturate(180%);
      -webkit-backdrop-filter: blur(32px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.9);
      border-radius: 32px;
      box-shadow:
        0 2px 0 rgba(255, 255, 255, 0.8) inset,
        0 40px 80px rgba(100, 130, 200, 0.18),
        0 8px 20px rgba(100, 130, 200, 0.1);
      display: grid;
      grid-template-columns: 1fr 1fr;
      overflow: hidden;
      animation: cardIn .7s cubic-bezier(.16, 1, .3, 1) both;
    }

    @keyframes cardIn {
      from {
        opacity: 0;
        transform: translateY(28px) scale(.97);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @media (max-width: 700px) {
      .card {
        grid-template-columns: 1fr;
      }

      .side-art {
        display: none;
      }
    }

    /* ── art side ── */
    .side-art {
      background: linear-gradient(145deg, #1a2a6c, #2a4090, #3b5fd4);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 40px;
      position: relative;
      overflow: hidden;
    }

    .side-art::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 30% 20%, rgba(255, 255, 255, .12), transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(120, 160, 255, .2), transparent 50%);
    }

    .side-art::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
      background-size: 40px 40px;
      animation: gridSlide 20s linear infinite;
    }

    @keyframes gridSlide {
      from {
        background-position: 0 0;
      }

      to {
        background-position: 40px 40px;
      }
    }

    .art-content {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .qr-box {
      width: 160px;
      height: 160px;
      margin: 0 auto 32px;
      position: relative;
      animation: qrFloat 5s ease-in-out infinite;
    }

    @keyframes qrFloat {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    .qr-box svg {
      width: 100%;
      height: 100%;
    }

    .scan-beam {
      position: absolute;
      left: 8px;
      right: 8px;
      height: 2px;
      background: linear-gradient(90deg, transparent, #7eb8ff, #a0cdff, #7eb8ff, transparent);
      border-radius: 2px;
      top: 8px;
      animation: scanBeam 2.8s ease-in-out infinite;
      box-shadow: 0 0 8px 2px rgba(126, 184, 255, .5);
    }

    @keyframes scanBeam {
      0% {
        top: 12px;
        opacity: 0;
      }

      10% {
        opacity: 1;
      }

      90% {
        opacity: 1;
      }

      100% {
        top: calc(100% - 12px);
        opacity: 0;
      }
    }

    .qr-corner {
      position: absolute;
      width: 20px;
      height: 20px;
      border-color: rgba(255, 255, 255, .7);
      border-style: solid;
    }

    .qr-corner.tl {
      top: 0;
      left: 0;
      border-width: 2px 0 0 2px;
      border-radius: 4px 0 0 0;
    }

    .qr-corner.tr {
      top: 0;
      right: 0;
      border-width: 2px 2px 0 0;
      border-radius: 0 4px 0 0;
    }

    .qr-corner.bl {
      bottom: 0;
      left: 0;
      border-width: 0 0 2px 2px;
      border-radius: 0 0 0 4px;
    }

    .qr-corner.br {
      bottom: 0;
      right: 0;
      border-width: 0 2px 2px 0;
      border-radius: 0 0 4px 0;
    }

    .art-tagline {
      font-size: .7rem;
      font-weight: 600;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .5);
      margin-bottom: 10px;
    }

    .art-title {
      font-size: 1.9rem;
      font-weight: 800;
      color: #fff;
      line-height: 1.1;
      margin-bottom: 10px;
    }

    .art-desc {
      font-size: .82rem;
      color: rgba(255, 255, 255, .55);
      line-height: 1.7;
    }

    .ring {
      position: absolute;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, .08);
      pointer-events: none;
    }

    .ring-1 {
      width: 260px;
      height: 260px;
      top: -60px;
      right: -60px;
      animation: ringPulse 6s ease-in-out infinite;
    }

    .ring-2 {
      width: 180px;
      height: 180px;
      bottom: -30px;
      left: -40px;
      animation: ringPulse 8s ease-in-out infinite reverse;
    }

    @keyframes ringPulse {

      0%,
      100% {
        transform: scale(1);
        opacity: .6;
      }

      50% {
        transform: scale(1.08);
        opacity: 1;
      }
    }

    /* ── form side ── */
    .side-form {
      padding: 52px 48px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 36px;
      animation: fadeUp .5s .1s ease both;
    }

    .brand-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      border: 1px solid #e4ecff;
      background: #f4f8ff;
      overflow: hidden;
      display: grid;
      place-items: center;
      flex-shrink: 0;
    }

    .brand-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .brand-text {
      font-size: .78rem;
      font-weight: 700;
      color: #1a2a6c;
      line-height: 1.3;
    }

    .brand-text small {
      display: block;
      font-weight: 400;
      color: #8fa0c8;
      font-size: .68rem;
    }

    .form-title {
      font-size: 2rem;
      font-weight: 800;
      color: #0d1b4b;
      line-height: 1.1;
      margin-bottom: 6px;
      animation: fadeUp .5s .15s ease both;
    }

    .form-caption {
      font-size: .82rem;
      color: #8fa0c8;
      margin-bottom: 30px;
      animation: fadeUp .5s .2s ease both;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(14px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .flash {
      background: #fff2f2;
      border: 1px solid #ffd6d6;
      color: #c0392b;
      border-radius: 12px;
      padding: 10px 14px;
      font-size: .8rem;
      font-weight: 600;
      margin-bottom: 18px;
    }

    .field-group {
      margin-bottom: 16px;
      animation: fadeUp .5s ease both;
    }

    .field-group:nth-child(1) {
      animation-delay: .25s;
    }

    .field-group:nth-child(2) {
      animation-delay: .32s;
    }

    .field-label {
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: #6b7fa8;
      margin-bottom: 8px;
      display: block;
    }

    .field-wrap {
      position: relative;
    }

    .field {
      width: 100%;
      background: #f4f7ff;
      border: 1.5px solid #e2e9ff;
      border-radius: 14px;
      padding: 13px 16px;
      font-family: 'Sora', sans-serif;
      font-size: .88rem;
      color: #0d1b4b;
      outline: none;
      transition: all .2s ease;
    }

    .field::placeholder {
      color: #b8c4df;
    }

    .field:focus {
      border-color: #6b8fff;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(107, 143, 255, .12);
    }

    .field:hover:not(:focus) {
      border-color: #c8d6ff;
    }

    .toggle-pass {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #b8c4df;
      cursor: pointer;
      font-size: .85rem;
      padding: 4px;
      transition: color .2s;
      line-height: 1;
    }

    .toggle-pass:hover {
      color: #6b8fff;
    }

    .forgot-row {
      text-align: right;
      margin: 10px 0 24px;
      animation: fadeUp .5s .38s ease both;
    }

    .forgot-link {
      font-size: .75rem;
      font-weight: 600;
      color: #8fa0c8;
      text-decoration: none;
      transition: color .2s;
    }

    .forgot-link:hover {
      color: #6b8fff;
    }

    /* ── squircle button ── */
    .btn-main {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 18px;
      clip-path: polygon(6% 0%, 94% 0%, 100% 6%, 100% 94%, 94% 100%, 6% 100%, 0% 94%, 0% 6%);
      font-family: 'Sora', sans-serif;
      font-size: .88rem;
      font-weight: 700;
      color: #fff;
      cursor: pointer;
      letter-spacing: .04em;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #2a4090, #4266d4, #3b5fd4);
      background-size: 200% 100%;
      transition: transform .2s, box-shadow .2s, background-position .4s;
      animation: fadeUp .5s .44s ease both;
      box-shadow: 0 12px 28px rgba(42, 64, 144, .3), 0 4px 8px rgba(42, 64, 144, .2);
    }

    .btn-main:hover {
      transform: translateY(-2px);
      box-shadow: 0 18px 36px rgba(42, 64, 144, .35), 0 6px 12px rgba(42, 64, 144, .2);
      background-position: 100% 0;
    }

    .btn-main:active {
      transform: translateY(0);
    }

    .btn-main::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -80%;
      width: 60%;
      height: 200%;
      background: linear-gradient(105deg, transparent, rgba(255, 255, 255, .22), transparent);
      transform: skewX(-20deg);
      animation: btnShine 3.5s ease-in-out infinite;
    }

    @keyframes btnShine {
      0% {
        left: -80%;
      }

      40% {
        left: 130%;
      }

      100% {
        left: 130%;
      }
    }

    .btn-main span {
      position: relative;
      z-index: 1;
    }

    .signup-note {
      text-align: center;
      margin-top: 20px;
      font-size: .78rem;
      color: #8fa0c8;
      animation: fadeUp .5s .5s ease both;
    }

    .signup-note a {
      color: #3b5fd4;
      font-weight: 700;
      text-decoration: none;
      border-bottom: 1.5px solid #c0d0ff;
      padding-bottom: 1px;
      transition: border-color .2s, color .2s;
    }

    .signup-note a:hover {
      border-color: #3b5fd4;
    }

    .modal-content {
      border-radius: 20px !important;
      border: 1px solid #e2e9ff !important;
      box-shadow: 0 20px 60px rgba(42, 64, 144, .15) !important;
    }

    .modal-header {
      border-bottom: 1px solid #f0f4ff !important;
    }

    .modal-title {
      font-weight: 800 !important;
      color: #0d1b4b !important;
      font-size: 1rem !important;
    }

    html,
    body {
      height: auto;
    }

    /* ✅ allow scrolling */
    body {
      font-family: 'Sora', sans-serif;
      background: #f0f4ff;

      /* instead of min-height:100vh + overflow:hidden */
      min-height: 100dvh;
      /* better on mobile than 100vh */
      padding: 20px;

      display: flex;
      align-items: center;
      justify-content: center;

      overflow-x: hidden;
      overflow-y: auto;
      /* ✅ enable vertical scroll */
      -webkit-overflow-scrolling: touch;
      /* ✅ smooth iOS scrolling */
    }

    /* ✅ keep background fixed but don't block scroll */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 15% 20%, #dce8ff 0%, transparent 55%),
        radial-gradient(ellipse 70% 50% at 85% 80%, #e4edff 0%, transparent 55%),
        #f0f4ff;
      z-index: 0;
      animation: meshShift 12s ease-in-out infinite alternate;
      pointer-events: none;
      /* ✅ important */
    }

    /* ✅ card becomes responsive and scroll-safe */
    .card {
      position: relative;
      z-index: 1;
      width: min(960px, 100%);

      /* ✅ don’t force a tall fixed layout */
      min-height: unset;
      max-height: calc(100dvh - 40px);
      /* ✅ fits viewport minus padding */
      overflow: hidden;

      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(32px) saturate(180%);
      -webkit-backdrop-filter: blur(32px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.9);
      border-radius: 32px;
      box-shadow:
        0 2px 0 rgba(255, 255, 255, 0.8) inset,
        0 40px 80px rgba(100, 130, 200, 0.18),
        0 8px 20px rgba(100, 130, 200, 0.1);

      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    /* ✅ make the form side scroll if content exceeds */
    .side-form {
      padding: 52px 48px;
      display: flex;
      flex-direction: column;
      justify-content: center;

      overflow-y: auto;
      /* ✅ internal scroll safety */
      -webkit-overflow-scrolling: touch;
    }

    /* ✅ mobile tweaks */
    @media (max-width: 700px) {
      body {
        padding: 14px;
        align-items: stretch;
        /* ✅ allow full height layout */
        justify-content: center;
      }

      .card {
        grid-template-columns: 1fr;
        border-radius: 22px;
        max-height: none;
        /* ✅ let it grow naturally */
        overflow: visible;
        /* ✅ use page scroll */
      }

      .side-art {
        display: none;
      }

      .side-form {
        padding: 28px 20px;
        overflow: visible;
        /* ✅ no trapped scroll */
      }

      .form-title {
        font-size: 1.65rem;
      }
    }

    /* ✅ optional: for very short screens (landscape phones) */
    @media (max-height: 640px) and (max-width: 900px) {
      .card {
        max-height: calc(100dvh - 24px);
      }

      .side-form {
        justify-content: flex-start;
      }
    }
  </style>
</head>

<body>

  <div class="blob blob-a"></div>
  <div class="blob blob-b"></div>

  <div class="card">

    <div class="side-art">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
      <div class="art-content">
        <div class="qr-box">
          <div class="qr-corner tl"></div>
          <div class="qr-corner tr"></div>
          <div class="qr-corner bl"></div>
          <div class="qr-corner br"></div>
          <div class="scan-beam"></div>
          <svg viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="12" y="12" width="48" height="48" rx="8" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="2" />
            <rect x="22" y="22" width="28" height="28" rx="4" fill="rgba(255,255,255,.15)" />
            <rect x="30" y="30" width="12" height="12" rx="2" fill="rgba(255,255,255,.8)" />
            <rect x="100" y="12" width="48" height="48" rx="8" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="2" />
            <rect x="110" y="22" width="28" height="28" rx="4" fill="rgba(255,255,255,.15)" />
            <rect x="118" y="30" width="12" height="12" rx="2" fill="rgba(255,255,255,.8)" />
            <rect x="12" y="100" width="48" height="48" rx="8" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="2" />
            <rect x="22" y="110" width="28" height="28" rx="4" fill="rgba(255,255,255,.15)" />
            <rect x="30" y="118" width="12" height="12" rx="2" fill="rgba(255,255,255,.8)" />
            <rect x="74" y="12" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="86" y="12" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="74" y="24" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="86" y="24" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="74" y="36" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="86" y="36" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="74" y="48" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="86" y="48" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="12" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="24" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="36" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="48" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="74" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.7)" />
            <rect x="86" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="98" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="110" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="122" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="134" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="146" y="74" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="74" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="86" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="98" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="110" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="122" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="134" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="146" y="86" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="74" y="98" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="86" y="98" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="98" y="98" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="110" y="98" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="122" y="98" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="74" y="110" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="86" y="110" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="98" y="110" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="110" y="110" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="74" y="122" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="86" y="122" width="8" height="8" rx="2" fill="rgba(255,255,255,.6)" />
            <rect x="98" y="122" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="74" y="134" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
            <rect x="86" y="134" width="8" height="8" rx="2" fill="rgba(255,255,255,.3)" />
            <rect x="74" y="146" width="8" height="8" rx="2" fill="rgba(255,255,255,.4)" />
            <rect x="86" y="146" width="8" height="8" rx="2" fill="rgba(255,255,255,.5)" />
          </svg>
        </div>
        <p class="art-tagline">Attendance Portal</p>
        <h2 class="art-title">FBMSO</h2>
        <p class="art-desc">Fast, secure check-ins<br>powered by QR codes</p>
      </div>
    </div>

    <div class="side-form">
      <div class="brand-row">
        <div class="brand-icon">
          <img src="<?= base_url(); ?>upload/banners/logo1.png" alt="Logo">
        </div>
        <div class="brand-text">
          Attendance Portal
          <small>Faculty of Business Management Student Org.</small>
        </div>
      </div>

      <h1 class="form-title">Sign in</h1>
      <p class="form-caption">Enter your credentials to continue</p>

      <?php
      $authError      = $this->session->flashdata('auth_error');
      $loginErrorText = is_string($authError) ? trim(strip_tags($authError)) : '';
      $infoMessage    = $this->session->flashdata('info_message') ?: '';
      ?>
      <?php if (!empty($loginErrorText)): ?>
        <div class="flash" id="login-error-message"><?= htmlspecialchars($loginErrorText, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <form action="<?= site_url('Login/auth'); ?>" method="post" novalidate>
        <input type="hidden" name="next" value="<?= html_escape($this->input->get('next')); ?>">
        <input type="hidden" name="sy" value="<?= isset($active_sy)  ? $active_sy  : ''; ?>">
        <input type="hidden" name="semester" value="<?= isset($active_sem) ? $active_sem : ''; ?>">

        <div class="field-group">
          <label class="field-label" for="username">Username</label>
          <div class="field-wrap">
            <input class="field" id="username" name="username" type="text" autocomplete="username" placeholder="Enter username" required>
          </div>
        </div>

        <div class="field-group">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <input class="field" id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required style="padding-right:42px">
            <button class="toggle-pass" type="button" id="togglePass" title="Toggle"><i class="fa fa-eye"></i></button>
          </div>
        </div>

        <div class="forgot-row">
          <a class="forgot-link" href="#" data-toggle="modal" data-target="#forgotModal">Forgot password?</a>
        </div>

        <button class="btn-main" type="submit"><span>Sign in</span></button>

        <?php if (isset($allow_signup) && $allow_signup == 'Yes'): ?>
          <p class="signup-note">No account? <a href="<?= base_url(); ?>Registration">Create one</a></p>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Forgot Modal -->
  <div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="font-family:'Sora',sans-serif; color:#0d1b4b;">
        <div class="modal-header px-4 pt-4 pb-3">
          <h5 class="modal-title" id="forgotLabel">Reset password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#8fa0c8"><span>&times;</span></button>
        </div>
        <div class="modal-body px-4 pb-4">
          <form id="resetPassword" method="post" action="<?= base_url(); ?>login/forgot_pass">
            <div class="field-group">
              <label class="field-label" for="reset-email">Email address</label>
              <input type="email" id="reset-email" name="email" class="field" placeholder="you@example.com" required>
            </div>
            <button class="btn-main" type="submit" style="margin-top:12px"><span>Send reset link</span></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url(); ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/popper.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <script>
    (function() {
      var btn = document.getElementById('togglePass');
      var ipt = document.getElementById('password');
      if (!btn || !ipt) return;
      btn.addEventListener('click', function() {
        var show = ipt.type === 'password';
        ipt.type = show ? 'text' : 'password';
        this.firstElementChild.className = show ? 'fa fa-eye-slash' : 'fa fa-eye';
      });
    })();

    (function() {
      var loginError = <?= json_encode($loginErrorText ?? ''); ?>;
      var infoMsg = <?= json_encode($infoMessage    ?? ''); ?>;
      if (!loginError && !infoMsg) return;
      var isErr = /invalid|incorrect|not active|failed|unauthorized|email not found/i.test(loginError || '');
      var opts = isErr ? {
        icon: 'error',
        title: 'Sign-in failed',
        text: loginError,
        confirmButtonColor: '#e74c3c'
      } : {
        icon: 'success',
        title: 'Done',
        text: infoMsg,
        confirmButtonColor: '#3b5fd4'
      };
      if (window.Swal) {
        Swal.fire(opts);
        var fb = document.getElementById('login-error-message');
        if (fb) fb.style.display = 'none';
      }
    })();
  </script>
</body>

</html>