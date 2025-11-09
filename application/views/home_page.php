<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('includes/title.php'); ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" type="<?= base_url(); ?>assets/image/png" href="<?= base_url(); ?>assets/images/Attendance.png" />
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css">

  <style>
    :root {
      --bg-1: #f6f9ff;
      --bg-2: #eef4ff;
      --brand-1: #3b82f6;
      --brand-2: #60a5fa;
      --ink: #0f2b46;
      --muted: #5b6c81;
      --panel: #ffffff;
      --ring: rgba(59, 130, 246, .25);
      --field: #f4f7ff;
      --field-bd: #d8e4ff;
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      background:
        radial-gradient(1400px 700px at 10% 0%, #e9f2ff, transparent 55%),
        radial-gradient(1400px 700px at 90% 100%, #eaf3ff, transparent 55%),
        linear-gradient(180deg, var(--bg-1), var(--bg-2));
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
      color: var(--muted);
    }

    .shell {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 16px
    }

    .frame {
      width: min(96vw, 1440px);
      min-height: min(88vh, 860px);
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 24px 80px rgba(2, 8, 23, .12);
      background: var(--panel);
    }

    @media (max-width: 992px) {
      .frame {
        grid-template-columns: 1fr;
        min-height: auto
      }

      .pane-form {
        order: -1
      }

      .pane-hero {
        order: 0
      }
    }

    .pane-hero {
      background:
        radial-gradient(900px 480px at 120% -10%, rgba(96, 165, 250, .25), transparent 60%),
        linear-gradient(180deg, #f0f7ff, #e8f2ff);
      padding: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero-card {
      width: 100%;
      max-width: 920px;
      background: var(--panel);
      border: 1px solid #e7efff;
      border-radius: 18px;
      box-shadow: 0 16px 40px rgba(59, 130, 246, .15);
      padding: 28px;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1.05fr 0.95fr;
      gap: 24px;
      align-items: center;
    }

    @media (max-width: 1200px) {
      .hero-grid {
        grid-template-columns: 1fr
      }
    }

    @media (max-width: 576px) {
      .pane-hero {
        display: none
      }
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      color: #0b3b77;
      font-weight: 700;
      background: #e8f2ff;
      border: 1px solid #d7e7ff;
      font-size: .95rem;
    }

    .hero-title {
      color: var(--ink);
      font-weight: 900;
      line-height: 1.05;
      margin: 12px 0 10px;
      font-size: 2.35rem
    }

    .hero-sub {
      margin: 0 0 12px
    }

    .pane-form {
      background: linear-gradient(180deg, #f8fbff, #f0f6ff);
      padding: 40px 36px;
      display: flex;
      flex-direction: column;
      border-left: 1px solid #e6eeff;
      outline: 0;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
      color: #0b3b77
    }

    .brand-logo {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      overflow: hidden;
      display: grid;
      place-items: center;
      background: #edf4ff;
      border: 1px solid #d7e7ff;
    }

    .title {
      color: var(--ink);
      font-weight: 900;
      margin: 6px 0 4px;
      font-size: 1.9rem
    }

    .sub {
      margin-bottom: 14px
    }

    .flash {
      background: #fff4f4;
      border: 1px solid #fecaca;
      color: #b91c1c;
      border-radius: 10px;
      padding: 10px 12px;
      font-weight: 600;
      margin-bottom: 12px
    }

    .form-label {
      font-weight: 700;
      color: #13385e
    }

    .form-control {
      background: var(--field);
      border: 1px solid var(--field-bd);
      color: #0e2f53;
      border-radius: 10px;
      padding: .78rem .95rem;
    }

    .form-control::placeholder {
      color: #93a7c7
    }

    .form-control:focus {
      background: #fff;
      border-color: var(--brand-2);
      box-shadow: 0 0 0 .22rem var(--ring);
      color: #0e2f53
    }

    .input-group-append .btn {
      border-color: var(--field-bd);
      background: #f3f7ff
    }

    .btn-brand {
      width: 100%;
      border: 0;
      border-radius: 12px;
      padding: .95rem 1rem;
      font-weight: 800;
      letter-spacing: .2px;
      color: #06223f;
      background: linear-gradient(180deg, var(--brand-2), var(--brand-1));
      box-shadow: 0 14px 34px rgba(59, 130, 246, .25);
    }

    .btn-brand:hover {
      filter: brightness(.98)
    }

    .link {
      color: var(--brand-1);
      font-weight: 800;
      text-decoration: none
    }

    .link:hover {
      text-decoration: underline
    }

    .foot-note {
      margin-top: auto;
      color: #6f85a4;
      font-size: .85rem
    }

    input:-webkit-autofill,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:hover {
      -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
      box-shadow: 0 0 0 30px #ffffff inset !important;
      -webkit-text-fill-color: #0e2f53 !important;
    }
  </style>
</head>

<body>
  <div class="shell">
    <div class="frame">
      <div class="pane-hero">
        <div class="hero-card">
          <div class="hero-grid">
            <div>
              <div class="badge-soft"><i class="fa fa-qrcode"></i>Attendance Portal</div>
              <h1 class="hero-title">FBMSO</h1>
              <small class="hero-sub">Faculty of Business Management Student Organization</small>
              <p class="hero-sub">Log in to access activities, generate QR codes, and view scan logs.</p>
            </div>
            <div class="hero-illustration" aria-hidden="true">
              <svg viewBox="0 0 560 360" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" role="img">
                <defs>
                  <linearGradient id="bgG" x1="0" x2="1" y1="0" y2="1">
                    <stop offset="0" stop-color="#e9f2ff" />
                    <stop offset="1" stop-color="#f7fbff" />
                  </linearGradient>
                  <linearGradient id="blueG" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0" stop-color="#60a5fa" />
                    <stop offset="1" stop-color="#3b82f6" />
                  </linearGradient>
                  <filter id="cardShadow" x="-20%" y="-20%" width="140%" height="140%">
                    <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#8ab6ff" flood-opacity=".25" />
                  </filter>
                </defs>

                <rect x="8" y="8" rx="22" width="544" height="344" fill="url(#bgG)" stroke="#e7efff" />
                <g filter="url(#cardShadow)">
                  <rect x="36" y="56" rx="16" width="310" height="215" fill="#fff" stroke="#e5eefc" />
                  <rect x="56" y="76" width="64" height="64" rx="8" fill="url(#blueG)" />
                  <rect x="68" y="88" width="40" height="40" rx="6" fill="#fff" opacity=".95" />
                  <rect x="266" y="76" width="64" height="64" rx="8" fill="url(#blueG)" />
                  <rect x="278" y="88" width="40" height="40" rx="6" fill="#fff" opacity=".95" />
                  <rect x="56" y="196" width="64" height="64" rx="8" fill="url(#blueG)" />
                  <rect x="68" y="208" width="40" height="40" rx="6" fill="#fff" opacity=".95" />
                  <rect x="148" y="120" width="22" height="22" rx="4" fill="#d5e6ff" />
                  <rect x="178" y="120" width="22" height="22" rx="4" fill="#c7ddff" />
                  <rect x="208" y="120" width="22" height="22" rx="4" fill="#d5e6ff" />
                  <rect x="148" y="150" width="22" height="22" rx="4" fill="#c7ddff" />
                  <rect x="178" y="150" width="22" height="22" rx="4" fill="#d5e6ff" />
                  <rect x="208" y="150" width="22" height="22" rx="4" fill="#c7ddff" />
                  <rect x="148" y="180" width="22" height="22" rx="4" fill="#d5e6ff" />
                  <rect x="178" y="180" width="22" height="22" rx="4" fill="#c7ddff" />
                  <rect x="208" y="180" width="22" height="22" rx="4" fill="#d5e6ff" />
                  <rect x="148" y="80" width="120" height="10" rx="5" fill="#d9e8ff" />
                  <rect x="148" y="96" width="160" height="10" rx="5" fill="#d9e8ff" opacity=".9" />
                  <rect x="148" y="216" width="96" height="10" rx="5" fill="#d9e8ff" />
                  <rect x="148" y="232" width="136" height="10" rx="5" fill="#d9e8ff" opacity=".9" />
                </g>
                <g filter="url(#cardShadow)">
                  <rect x="384" y="104" rx="26" width="130" height="212" fill="#fff" stroke="#e5eefc" />
                  <rect x="394" y="144" rx="12" width="110" height="132" fill="#f6faff" stroke="#e8f1ff" />
                  <rect x="418" y="168" width="62" height="62" rx="10" fill="url(#blueG)" />
                  <rect x="430" y="180" width="38" height="38" rx="8" fill="#fff" opacity=".95" />
                  <rect x="418" y="238" width="80" height="10" rx="5" fill="#d9e8ff" />
                  <rect x="418" y="255" width="72" height="10" rx="5" fill="#d9e8ff" />
                  <circle cx="449" cy="124" r="6" fill="#e0eaff" />
                </g>
                <circle cx="500" cy="72" r="22" fill="#e8f2ff" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div class="pane-form" tabindex="-1">
        <div class="brand">
          <div class="brand-logo">
            <img src="<?= base_url(); ?>upload/banners/logo1.png" alt="Institution Logo"
              style="width:100%; height:100%; object-fit:contain">
          </div>
          <div>
            <h1 class="mb-0" style="font-weight:800; font-size:1.1rem;">Attendance Portal</h1>
            <small>QR-based check-in</small>
          </div>
        </div>

        <h2 class="title">Login</h2>
        <div class="sub">Enter your account details</div>
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

          <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" id="username" name="username" type="text" autocomplete="username" placeholder="Username" required>
          </div>

          <div class="mb-2">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
              <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="togglePass" title="Show/Hide"><i class="fa fa-eye"></i></button>
              </div>
            </div>
            <div class="mt-2">
              <a class="link" href="#" data-toggle="modal" data-target="#forgotModal">Forgot password?</a>
            </div>
          </div>

          <input type="hidden" name="sy" value="<?= isset($active_sy) ? $active_sy : ''; ?>">
          <input type="hidden" name="semester" value="<?= isset($active_sem) ? $active_sem : ''; ?>">

          <div class="mt-3">
            <button class="btn btn-brand" type="submit">Login</button>
          </div>

          <div class="mt-3">
            <?php if (isset($allow_signup) && $allow_signup == 'Yes'): ?>
              <span>Don’t have an account?</span>
              <a class="link" href="<?= base_url(); ?>Registration">Sign up</a>
            <?php endif; ?>
          </div>
        </form>

        <div class="foot-note mt-4">
          Fast, secure check-ins with QR codes.
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
      if (btn && ipt) {
        btn.addEventListener('click', function() {
          var isPwd = ipt.type === 'password';
          ipt.type = isPwd ? 'text' : 'password';
          this.firstElementChild.className = isPwd ? 'fa fa-eye-slash' : 'fa fa-eye';
        });
      }
    })();
    (function() {
      function focusLogin() {
        var formPane = document.querySelector('.pane-form');
        var user = document.getElementById('username');
        if (!formPane) return;
        if (window.matchMedia('(max-width: 992px)').matches) {
          formPane.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          if (user) {
            user.setAttribute('autofocus', 'autofocus');
            user.focus({
              preventScroll: false
            });
          }
        }
      }
      window.addEventListener('load', focusLogin);
    })();
  </script>
  <script>
    (function() {
      var loginError = <?= json_encode($loginErrorText ?? ''); ?>; // from auth_error
      var infoMsg = <?= json_encode($infoMessage ?? ''); ?>; // from info_message

      if (!loginError && !infoMsg) return;

      var isAuthError = /invalid|incorrect|not active|failed|unauthorized|email not found/i.test(loginError || '');

      var opts = isAuthError ? {
        icon: 'error',
        title: 'Sign-in failed',
        text: loginError,
        confirmButtonColor: '#dc3545'
      } : {
        icon: 'info',
        title: 'Success',
        text: infoMsg,
        confirmButtonColor: '#0d6efd'
      };

      var fallback = document.getElementById('login-error-message');
      if (window.Swal && typeof window.Swal.fire === 'function') {
        window.Swal.fire(opts);
        if (fallback) fallback.style.display = 'none';
      } else if (fallback && (loginError || infoMsg)) {
        // Fallback to inline message only for auth errors
        if (loginError) {
          fallback.style.display = 'block';
          fallback.textContent = loginError;
        }
      }
    })();
  </script>


  <div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" aria-hidden="true" style="color:#111">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="forgotModalLabel">Forgot Password</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form id="resetPassword" method="post" action="<?= base_url(); ?>login/forgot_pass">
            <div class="input-group mb-3">
              <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
              <div class="input-group-append">
                <div class="input-group-text"><span class="fa fa-envelope"></span></div>
              </div>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Request a New Password</button>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>