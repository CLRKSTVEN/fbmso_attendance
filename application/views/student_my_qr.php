<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php'); ?>

<body class="antialiased">
  <div id="wrapper">
    <?php include('includes/top-nav-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <style>
      /* ===== Root & utilities ===== */
      :root{
        --bg:#f8fafc;--card:#ffffff;--muted:#64748b;--line:#e5e7eb;--brand:#2563eb;--brand-600:#2563eb;--brand-700:#1d4ed8;--success:#16a34a;--warning:#f59e0b;--info:#0ea5e9;
      }
      @media (prefers-color-scheme: dark){
        :root{--bg:#0b1220;--card:#0f172a;--muted:#94a3b8;--line:#1e293b;--brand:#3b82f6;--brand-600:#3b82f6;--brand-700:#2563eb;--success:#22c55e;--warning:#fbbf24;--info:#38bdf8}
        body{color:#e2e8f0}
      }
      html{scroll-behavior:smooth}
      body{background:var(--bg)}
      .visually-hidden{position:absolute!important;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
      .text-mono{font-family:ui-monospace,Menlo,Consolas,monospace}
      .shadow-soft{box-shadow:0 8px 30px rgba(2,6,23,.06)}
      .rounded-2xl{border-radius:16px}
      .content-pad{padding:18px}
      .gap-2{gap:.5rem}
      .gap-3{gap:.75rem}
      .safe-bottom{padding-bottom:calc(16px + env(safe-area-inset-bottom))}

      /* ===== Header ===== */
      .page-title-box h4{margin:0 0 .25rem;font-weight:800}
      .page-sub{color:var(--muted);font-size:.92rem}
      .divider{border:0;height:2px;background:linear-gradient(90deg,#3b82f6,#f59e0b 60%,#22c55e);border-radius:1px;margin:10px 0 16px}

      /* ===== Card ===== */
      .card-clean{background:var(--card);border:1px solid var(--line)}
      .card-clean .card-header{background:color-mix(in srgb,var(--card) 92%,#fff 8%);border-bottom:1px solid var(--line);padding:.75rem 1rem;font-weight:700}

      /* ===== QR Panel ===== */
      #qrcode{width:min(82vw,320px);aspect-ratio:1/1;border-radius:12px;border:1px dashed var(--line);background:#fff;max-width:320px}
      .chip{display:inline-flex;align-items:center;gap:.4rem;padding:.25rem .6rem;border-radius:999px;border:1px solid var(--line);background:var(--card);color:#334155;font-weight:700;font-size:.8rem}
      .chip i{font-size:14px;opacity:.75}
      .qr-actions .btn{min-width:150px}
      @media (min-width: 992px){ .qr-sticky{position:sticky; top:84px} }

      /* ===== Attendance ===== */
      .section-h{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem}
      .section-h h5{margin:0;font-weight:800;color:#0f172a}
      @media (prefers-color-scheme: dark){ .section-h h5{color:#e2e8f0} }
      .range-group .btn{border-radius:999px!important;padding:.4rem .8rem;font-weight:800}
      .range-group .btn.active{background:var(--brand-600);color:#fff;border-color:var(--brand-600)}

      .table-wrap{border-radius:12px;overflow:hidden;border:1px solid var(--line)}
      #myAttTable{margin:0}
      #myAttTable thead th{white-space:nowrap;background:color-mix(in srgb,var(--card) 95%,#fff 5%);border-bottom:1px solid var(--line)}
      #myAttTable td,#myAttTable th{vertical-align:middle}
      #myAttTable tbody tr:hover{background:color-mix(in srgb,var(--card) 92%,#3b82f6 8%)}
.pill{display:inline-block;border-radius:999px;padding:.18rem .55rem;font-size:.75rem;font-weight:800}
.pill-ses{background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
.badge-soft{background:#eef2ff;color:#1e3a8a}
.muted-hint{color:var(--muted);font-size:.85rem;margin-top:.6rem}

.att-card{border:1px solid var(--line);border-radius:14px;background:var(--card);box-shadow:0 4px 14px rgba(15,23,42,.08);margin-bottom:12px;overflow:hidden}
.att-card-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:color-mix(in srgb,var(--card) 92%,#fff 8%);border-bottom:1px solid var(--line);color:#0f172a;font-weight:700;font-size:14px}
.att-card-toggle{border:none;background:none;padding:0;margin:0;color:inherit;display:flex;flex-direction:column;align-items:flex-start;text-align:left;width:100%}
.att-card-title{display:flex;align-items:center;gap:.4rem;flex-wrap:wrap}
.att-card-date{font-size:12px;color:var(--muted);font-weight:400}
.att-card-session{margin-left:auto}
.att-card-toggle .toggle-icon{margin-left:8px;font-size:16px;transition:transform .2s ease}
.att-card-body{padding:12px 16px;background:var(--card)}
.att-detail-row{display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--ink);gap:1rem}
.att-detail-label{color:var(--muted);font-weight:600}
.att-detail-value{text-align:right;flex:1}
.status-badge{display:inline-block;padding:.25rem .6rem;border-radius:999px;font-size:.75rem;font-weight:700}
.status-open{background:#fef3c7;color:#b45309;border:1px solid #fcd34d}
.status-complete{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}

/* ===== Mobile-first responsive table ===== */
@media (max-width: 575.98px){
  .col-sm-hide{display:none}
  .qr-actions .btn{min-width:auto}
}
      @media (max-width: 420px){
        .btn{padding:.45rem .7rem}
        .btn i{margin-right:.2rem}
      }

      /* ===== Modal scanner ===== */
      .scan-wrap{position:relative;width:100%;max-width:720px;margin:0 auto}
      #reader{width:100%;min-height:320px;background:#000;border-radius:12px;overflow:hidden}
      #scanStatus{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);background:rgba(17,24,39,.7);color:#fff;border:1px solid rgba(255,255,255,.15);padding:6px 12px;border-radius:999px;font-size:.85rem;backdrop-filter:blur(4px)}
      #reader button,#reader input[type=range]{margin:6px}

      /* ===== Motion preference ===== */
      @media (prefers-reduced-motion: reduce){
        *{scroll-behavior:auto!important;animation:none!important;transition:none!important}
      }
      /* --- Header: make it truly responsive --- */
.page-title-box{
  display:flex;
  align-items:flex-end;
  justify-content:space-between;
  flex-wrap:wrap;
  gap:.5rem 1rem;
}

/* compact badge on narrow screens */
.badge.badge-info{white-space:nowrap}

/* Phone layout */
@media (max-width: 575.98px){
  .page-title-box{align-items:flex-start}

  /* Title line can wrap; badge moves nicely after the title text */
  .page-title-box h4{
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:.35rem .5rem;
    margin-bottom:.25rem;
  }

  /* Put the left block (title/subtitle) on its own row */
  .page-title-box > div:first-child{
    flex:1 1 100%;
    min-width:0;
  }

  /* Actions become full-width buttons in their own row */
  .header-actions{
    flex:1 1 100%;
    display:flex;
    gap:.5rem;
  }
  .header-actions .btn{
    flex:1 0 0;           /* both buttons same width */
    min-width:0;
  }

  /* Slightly smaller badge & subtitle so they don't wrap awkwardly */
  .page-title-box .badge{font-size:.72rem; padding:.25rem .5rem}
  .page-sub{font-size:.86rem}
}

/* Small tablets: keep actions side-by-side but allow wrapping */
@media (min-width:576px) and (max-width:767.98px){
  .header-actions{display:flex; gap:.5rem; flex-wrap:wrap}
  .header-actions .btn{flex:1}
}

    </style>

    <div class="content-page page-shell">
      <div class="content">
        <div class="container-fluid safe-bottom">

          <!-- Header -->
          <div class="row">
            <div class="col-12">
           <div class="page-title-box d-flex align-items-end justify-content-between flex-wrap gap-2">
  <div>
    <h4 class="page-title d-flex align-items-center">
      <i class="ion ion-ios-qr-scanner mr-2" aria-hidden="true"></i>
      My Permanent QR
      <span class="badge badge-info ml-2">For All Activities</span>
    </h4>
    <div class="page-sub">Use this code for all activities.</div>
  </div>

  <!-- NEW wrapper -->
  <div class="header-actions">
    <button id="btnOpenScanner" class="btn btn-primary btn-sm" aria-haspopup="dialog">
      <i class="mdi mdi-qrcode-scan" aria-hidden="true"></i> <span>Scan QR</span>
    </button>
    <button id="btnToggleQR" class="btn btn-light btn-sm">
      <i class="mdi mdi-eye-off-outline" aria-hidden="true"></i>
      <span class="d-none d-sm-inline">Hide QR</span>
    </button>
  </div>
</div>

              <hr class="divider" />
            </div>
          </div>

          <!-- Content -->
          <div class="row" id="gridRow">
            <!-- LEFT: QR & actions -->
            <div id="colQR" class="col-lg-5 collapsible">
              <div class="qr-sticky">
                <div class="card-clean shadow-soft rounded-2xl">
                  <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                      <div>
                        <div class="small text-uppercase text-muted mb-1 font-weight-600">Student No.</div>
                        <span class="chip" aria-label="Student Number"><span class="text-mono"><?= htmlspecialchars($student_number) ?></span></span>
                      </div>
                      <div>
                        <div class="small text-uppercase text-muted mb-1 font-weight-600">Status</div>
                        <span class="chip" style="border-color:#bbf7d0;background:#ecfdf5;color:#166534">
                          <i class="ion ion-md-checkmark-circle-outline" aria-hidden="true"></i><?= htmlspecialchars(($status ?? 'active')); ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="content-pad text-center">
                    <div id="qrcode" class="mx-auto mb-3" role="img" aria-label="Your permanent QR code"></div>

                    <div class="qr-actions d-flex justify-content-center flex-wrap gap-2 mb-3">
                      <button id="btnDownload" class="btn btn-primary">
                        <i class="mdi mdi-download" aria-hidden="true"></i> Download PNG
                      </button>
                      <button id="btnPrint" class="btn btn-outline-secondary">
                        <i class="mdi mdi-printer" aria-hidden="true"></i> Print
                      </button>
                    </div>

                    <hr class="my-3" />
                    <ul class="soft-note list-unstyled mb-0 text-left mx-auto" style="max-width:520px;color:var(--muted)">
                      <li class="mb-2">Show this QR to the <b>Heads</b> for scanning.</li>
                      <li class="mb-2">Or use <b>Scan QR</b> to scan poster codes for self check-in.</li>
                      <li>Having trouble? Ask the registrar or event staff.</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- RIGHT: Attendance -->
            <div id="colAtt" class="col-lg-7">
              <div class="alert alert-info d-flex align-items-center" role="status">
                <i class="mdi mdi-information-outline mr-2" aria-hidden="true"></i>
                This QR is permanent and works for all co-curricular activities.
              </div>

              <div class="section-h mb-2">
                <div>
                  <h5>My Attendance</h5>
                  <div class="text-muted">Filter by recency to find a specific session quickly.</div>
                </div>
                <div class="btn-group btn-group-sm range-group" role="group" aria-label="Filter by date range">
                  <button class="btn btn-light active" data-range="all">All</button>
                  <button class="btn btn-light" data-range="today">Today</button>
                  <button class="btn btn-light" data-range="7">Last 7 days</button>
                  <button class="btn btn-light" data-range="30">Last 30 days</button>
                </div>
              </div>

              <div class="table-wrap shadow-soft rounded-2xl">
                <div class="table-responsive d-none d-md-block" style="-webkit-overflow-scrolling:touch">
                  <table class="table table-sm table-hover mb-0" id="myAttTable">
                    <thead>
                      <tr>
                        <th style="width:56px;" class="col-sm-hide">#</th>
                        <th>Activity</th>
                        <th style="width:120px;" class="col-sm-hide">Session</th>
                        <th style="width:160px;">In</th>
                        <th style="width:160px;" class="col-sm-hide">Out</th>
                        <th style="width:110px;">Status</th>
                        <th style="width:90px;" class="col-sm-hide">Duration</th>
                        <th style="width:100px;" class="col-sm-hide">Source</th>
                        <th class="col-sm-hide">Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td colspan="9" class="text-center text-muted">Loading…</td></tr>
                    </tbody>
                  </table>
                </div>
                <div id="mobileAttList" class="d-md-none px-2 py-3">
                  <div class="text-center text-muted py-3">Loading…</div>
                </div>
              </div>

              <div class="muted-hint">Status: “Open” means you’ve checked-in but not yet checked-out for that session.</div>
            </div>
          </div>
          <!-- /Content -->

        </div>
      </div>

      <?php include('includes/footer.php'); ?>
    </div>
  </div>

  <?php include('includes/themecustomizer.php'); ?>

  <!-- QRCode -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <!-- html5-qrcode for camera scanning -->
  <script src="https://unpkg.com/html5-qrcode"></script>

  <!-- Vendor bundle (unchanged) -->
  <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/calendar.init.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>
  <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>
  <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
  <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

  <!-- ===== Student page logic ===== -->
  <script>
    (function () {
      /* ===== CI-aware base for check-in (works with subfolders / index.php) ===== */
      const CHECKIN_BASE = <?= json_encode(rtrim(site_url('attendance/checkin/'),'/') . '/'); ?>;

      /* ===== Permanent QR render ===== */
      const token = <?= json_encode($token) ?>;
      const qrEl  = document.getElementById('qrcode');
      new QRCode(qrEl, { text: token, width: qrEl.clientWidth, height: qrEl.clientWidth });
      // Resize QR on viewport changes
      const ro = new ResizeObserver(entries => {
        for (const e of entries){
          const s = Math.floor(e.contentRect.width);
          qrEl.innerHTML = '';
          new QRCode(qrEl, { text: token, width: s, height: s });
        }
      });
      ro.observe(qrEl);

      document.getElementById('btnDownload').addEventListener('click', function () {
        const img = qrEl.querySelector('img') || qrEl.querySelector('canvas');
        if (!img) return;
        const dataUrl = img.tagName.toLowerCase()==='img' ? img.src : img.toDataURL('image/png');
        const a = document.createElement('a'); a.href = dataUrl; a.download = 'my-qr.png';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
      });

      document.getElementById('btnPrint').addEventListener('click', function () {
        const img = qrEl.querySelector('img') || qrEl.querySelector('canvas');
        if (!img) return;
        const dataUrl = img.tagName.toLowerCase()==='img' ? img.src : img.toDataURL('image/png');
        const win = window.open('', 'printwin');
        win.document.write(`
          <html><head><title>My QR</title>
          <style>@media print{body{margin:0}}body{display:flex;align-items:center;justify-content:center;height:100vh;background:#fff}img{width:480px;height:480px}</style>
          </head><body><img src="${dataUrl}" alt="QR">
          <script>window.onload=function(){window.print();setTimeout(()=>window.close(),100)}<\/script>
          </body></html>`);
        win.document.close();
      });

      /* ===== Show/Hide QR logic ===== */
      const colQR  = document.getElementById('colQR');
      const colAtt = document.getElementById('colAtt');
      const btnTgl = document.getElementById('btnToggleQR');

      function setQrHidden(hidden){
        if (hidden){
          colQR.classList.add('d-none');
          colAtt.classList.remove('col-lg-7');
          colAtt.classList.add('col-lg-12');
          btnTgl.innerHTML = '<i class="mdi mdi-eye-outline" aria-hidden="true"></i> <span class="d-none d-sm-inline">Show QR</span>';
          localStorage.setItem('qrHidden','1');
        } else {
          colQR.classList.remove('d-none');
          colAtt.classList.remove('col-lg-12');
          colAtt.classList.add('col-lg-7');
          btnTgl.innerHTML = '<i class="mdi mdi-eye-off-outline" aria-hidden="true"></i> <span class="d-none d-sm-inline">Hide QR</span>';
          localStorage.setItem('qrHidden','0');
        }
      }
      // Default hidden the first time (if no preference saved)
      (function(){
        const stored = localStorage.getItem('qrHidden');
        setQrHidden(stored ? (stored === '1') : true);
      })();
      btnTgl.addEventListener('click', ()=> setQrHidden(colQR.classList.contains('d-none') ? false : true));

      /* ===== Attendance table ===== */
      const tbody = document.querySelector('#myAttTable tbody');
      const rangeBtns = document.querySelectorAll('[data-range]');
      let allRows = [];
      const mobileList = document.getElementById('mobileAttList');
      const escapeHtml = function (str) {
        return String(str ?? '').replace(/[&<>\"']/g, function (c) {
          return ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'}[c]);
        });
      };

      function sesLbl(s){ return ({am:'Morning', pm:'Afternoon', eve:'Evening'})[s||''] || '—'; }
      function fmt(iso){ if(!iso) return ''; try{ return moment(iso).format('hh:mm:ss A'); }catch(e){ return iso; } }
      function dur(a,b){
        if (!a || !b) return '';
        const mins = Math.max(0, Math.round((new Date(b)-new Date(a))/60000));
        if (mins < 60) return mins+'m';
        const h=Math.floor(mins/60), m=mins%60;
        return h+'h '+(m?m+'m':'');
      }
      function statusBadge(row){
        const open = !row.checked_out_at;
        return open ? '<span class="badge badge-warning">Open</span>'
                    : '<span class="badge badge-success">Completed</span>';
      }
      function srcBadge(src){
        const s=(src||'').toLowerCase();
        if (s==='qr') return '<span class="badge badge-soft">QR</span>';
        if (s==='manual') return '<span class="badge badge-secondary">Manual</span>';
        return '<span class="badge badge-light">—</span>';
      }
      function withinRange(row, range){
        if (range==='all') return true;
        const t=row.checked_in_at||row.checked_out_at; if (!t) return false;
        const d=moment(t);
        if (range==='today') return d.isSame(moment(),'day');
        const days=parseInt(range,10);
        return d.isAfter(moment().subtract(days,'days'));
      }

      function render(rows){
        tbody.innerHTML = '';
        if (mobileList) mobileList.innerHTML = '';
        if (!rows.length){
          tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No attendance yet.</td></tr>';
          if (mobileList) mobileList.innerHTML = '<div class="text-center text-muted py-3">No attendance yet.</div>';
          return;
        }
        let mobileHtml = '';
        rows.forEach((r,i)=>{
          const titleRaw = (r.title && r.title.trim()) ? r.title : (r.activity_id ? ('Activity #' + r.activity_id) : 'Activity');
          const title = escapeHtml(titleRaw);
          const dateStr = r.activity_date ? moment(r.activity_date).format('MMM D, YYYY') : '';
          const sessionLabel = sesLbl(r.session);
          const checkIn = r.checked_in_at ? fmt(r.checked_in_at) : '�';
          const checkOut = r.checked_out_at ? fmt(r.checked_out_at) : '�';
          const durationText = r.checked_out_at ? dur(r.checked_in_at, r.checked_out_at) : '�';
          const statusHtml = statusBadge(r);
          const sourceHtml = srcBadge(r.source);
          const remarksText = (r.remarks && r.remarks.trim()) ? escapeHtml(r.remarks) : (String(r.source).toLowerCase()==='qr' ? 'Scanned via QR' : '�');
          const tr = document.createElement('tr');
          tr.innerHTML =
            '<td class="col-sm-hide">'+(i+1)+'</td>'+
            '<td>'+
              '<div class="activity-title">'+title+'</div>'+
              (dateStr?'<small class="text-muted activity-date"><i class="ion ion-md-calendar mr-1" aria-hidden="true"></i>'+escapeHtml(dateStr)+'</small>':'')+
            '</td>'+
            '<td class="col-sm-hide"><span class="pill pill-ses">'+escapeHtml(sessionLabel)+'</span></td>'+
            '<td>'+ checkIn +'</td>'+
            '<td class="col-sm-hide">'+ checkOut +'</td>'+
            '<td>'+ statusHtml +'</td>'+
            '<td class="col-sm-hide">'+ (r.checked_out_at?durationText:'') +'</td>'+
            '<td class="col-sm-hide">'+ sourceHtml +'</td>'+
            '<td class="col-sm-hide">'+ remarksText +'</td>';
          tbody.appendChild(tr);

          if (mobileList) {
            const collapseId = 'att-card-' + i;
            mobileHtml += `
              <div class="att-card">
                <div class="att-card-header">
                  <button type="button" class="att-card-toggle collapsed" data-toggle="collapse" data-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                    <div class="d-flex flex-column text-left">
                      <span class="att-card-title">${title}</span>
                      ${dateStr ? `<span class="att-card-date">${escapeHtml(dateStr)}</span>` : ''}
                    </div>
                    <span class="toggle-icon">+</span>
                  </button>
                  <span class="pill-inline">${escapeHtml(sessionLabel)}</span>
                </div>
                <div id="${collapseId}" class="collapse" data-parent="#mobileAttList">
                  <div class="att-card-body">
                    <div class="att-detail-row"><span class="att-detail-label">Check-In</span><span class="att-detail-value">${escapeHtml(checkIn)}</span></div>
                    <div class="att-detail-row"><span class="att-detail-label">Check-Out</span><span class="att-detail-value">${escapeHtml(checkOut)}</span></div>
                    <div class="att-detail-row"><span class="att-detail-label">Duration</span><span class="att-detail-value">${escapeHtml(durationText)}</span></div>
                    <div class="att-detail-row"><span class="att-detail-label">Status</span><span class="att-detail-value">${statusHtml}</span></div>
                    <div class="att-detail-row"><span class="att-detail-label">Source</span><span class="att-detail-value">${sourceHtml}</span></div>
                    <div class="att-detail-row"><span class="att-detail-label">Remarks</span><span class="att-detail-value">${remarksText}</span></div>
                  </div>
                </div>
              </div>`;
          }
        });
        if (mobileList) mobileList.innerHTML = mobileHtml;
      }
      function applyRange(range){
        const filtered=allRows.filter(r=>withinRange(r,range));
        render(filtered);
        rangeBtns.forEach(b=>b.classList.toggle('active', b.getAttribute('data-range')===range));
      }

      fetch('<?= site_url('attendance/my_logs') ?>')
        .then(r=>r.json())
        .then(j=>{
          allRows=(j&&j.ok&&Array.isArray(j.rows))?j.rows:[];
          allRows.sort((a,b)=> (a.checked_in_at<b.checked_in_at)?1:-1);
          applyRange('all');
        })
        .catch(()=>{ tbody.innerHTML='<tr><td colspan="9" class="text-center text-danger">Failed to load.</td></tr>'; });

      rangeBtns.forEach(btn=>{ btn.addEventListener('click',()=>applyRange(btn.getAttribute('data-range'))); });

      /* ===== Modal scanner (hidden by default) ===== */
      // Modal markup injected once (keeps your view clean)
      const modalHtml = `
<div class="modal fade" id="studentScanModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="scanTitle">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content rounded-2xl overflow-hidden">
      <div class="modal-header">
        <h5 class="modal-title" id="scanTitle"><i class="mdi mdi-qrcode-scan mr-1" aria-hidden="true"></i> Scan Poster QR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="d-flex flex-wrap align-items-center mb-2 gap-2">
          <label for="cameraSelect" class="mb-0 mr-1">Camera:</label>
          <select id="cameraSelect" class="form-control form-control-sm" style="min-width:220px"></select>
          <button id="btnStart" class="btn btn-sm btn-success"><i class="mdi mdi-play" aria-hidden="true"></i> Start</button>
          <button id="btnStop"  class="btn btn-sm btn-outline-secondary"><i class="mdi mdi-stop" aria-hidden="true"></i> Stop</button>
          <button id="btnUpload" class="btn btn-sm btn-info"><i class="mdi mdi-upload" aria-hidden="true"></i> Upload</button>
          <input type="file" id="qrFileInput" accept="image/*" class="d-none" aria-label="Upload QR image">
          <label class="ml-2 mb-0 align-items-center d-inline-flex" title="Fix mirrored front cams">
            <input id="toggleDisableFlip" type="checkbox" class="mr-1" /> Front-cam fix
          </label>

          <!-- IN/OUT mode -->
          <div class="ml-auto d-flex align-items-center" style="gap:.5rem">
            <span class="text-muted small">Mode:</span>
            <div class="btn-group btn-group-sm" role="group" aria-label="Scan mode">
              <button id="sModeIn"  type="button" class="btn btn-success active">IN</button>
              <button id="sModeOut" type="button" class="btn btn-outline-primary">OUT</button>
            </div>
          </div>
        </div>
        <div class="scan-wrap">
          <div id="reader"></div>
          <div id="scanStatus" aria-live="polite">Starting camera…</div>
        </div>
        <small class="text-muted d-block mt-2">
          Tip: Fill the square with the poster QR. On laptops, try the <b>Front-cam fix</b> if the camera is mirrored.
        </small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>`;
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      const btnOpen = document.getElementById('btnOpenScanner');
      btnOpen.addEventListener('click', ()=> $('#studentScanModal').modal({backdrop:'static',keyboard:false}));

      // Scanner code attaches when modal is shown, and stops when hidden
      let qr = null, running=false, starting=false, stopping=false, devicesLoaded=false, forceDisableFlip=false;

      // ===== Student scan mode (IN / OUT)
      let scanMode = (localStorage.getItem('studentScanMode') === 'out') ? 'out' : 'in';

      function renderModeButtons(){
        const inBtn  = document.getElementById('sModeIn');
        const outBtn = document.getElementById('sModeOut');
        if (!inBtn || !outBtn) return;
        if (scanMode === 'in'){
          inBtn.classList.add('btn-success','active');
          inBtn.classList.remove('btn-outline-success');
          outBtn.classList.add('btn-outline-primary');
          outBtn.classList.remove('btn-primary','active');
        } else {
          outBtn.classList.add('btn-primary','active');
          outBtn.classList.remove('btn-outline-primary');
          inBtn.classList.add('btn-outline-success');
          inBtn.classList.remove('btn-success','active');
        }
      }
      function setScanMode(m){
        scanMode = (m === 'out') ? 'out' : 'in';
        localStorage.setItem('studentScanMode', scanMode);
        renderModeButtons();
      }
      document.addEventListener('click', function(e){
        if (e.target && e.target.id === 'sModeIn')  setScanMode('in');
        if (e.target && e.target.id === 'sModeOut') setScanMode('out');
      });

      function setStatus(t,cls){
        const el = document.getElementById('scanStatus');
        if (!el) return;
        el.textContent = t; el.className=''; if (cls) el.classList.add(cls);
      }
      function resizeReader(){
        const readerEl = document.getElementById('reader');
        if (!readerEl) return;
        const isSmall = window.innerWidth < 768;
        const w = readerEl.clientWidth || 480;
    const ar = isSmall ? (4/3) : (16/9);
readerEl.style.height = Math.round(w / ar) + 'px';

      }
      function extractActivityIdFrom(anyString) {
        const m = String(anyString).match(/attendance\/checkin\/(\d+)/i);
        return m ? m[1] : null;
      }
      function goToCheckin(id) {
        const base = CHECKIN_BASE + String(id);
        const hasQuery = base.includes('?');
        const url = base + (hasQuery ? '&' : '?') + 'direction=' + encodeURIComponent(scanMode);
        window.location.href = url;
      }

      async function enumerateCameras(){
        const camSel = document.getElementById('cameraSelect');
        try{
          const devs = await Html5Qrcode.getCameras();
          camSel.innerHTML='';
          if (!devs || !devs.length){ setStatus('No camera found','text-danger'); return []; }
          let backIndex=-1;
          devs.forEach((d,i)=>{
            const opt=document.createElement('option');
            opt.value=d.id; opt.textContent=d.label || ('Camera '+(i+1));
            if (backIndex===-1 && /back|rear|environment/i.test(d.label||'')) backIndex=i;
            camSel.appendChild(opt);
          });
          camSel.selectedIndex = (backIndex!==-1)? backIndex : 0;
          devicesLoaded = true; return devs;
        }catch(e){ setStatus('Camera error','text-danger'); return []; }
      }

      function cfg(env){
        const isDesktop = !/Android|iPhone|iPad|iPod/i.test(navigator.userAgent||'');
        if (env==='ios') {
          return {
            fps: 18, rememberLastUsedCamera: true, willReadFrequently: true, disableFlip: !!forceDisableFlip,
            experimentalFeatures: { useBarCodeDetectorIfSupported: true },
            videoConstraints: { facingMode:{ideal:'environment'}, width:{ideal:1920}, height:{ideal:1080} }
          };
        }
        return {
          fps: 24,
          qrbox: isDesktop ? function(w,h){ const s=Math.floor(Math.min(w,h)*0.72); return {width:s,height:s}; } : undefined,
          aspectRatio: isDesktop ? 1.3333 : (window.innerWidth<768?1.3333:1.7778),
          rememberLastUsedCamera: true, showTorchButtonIfSupported: true, willReadFrequently: true,
          experimentalFeatures: { useBarCodeDetectorIfSupported: true }, disableFlip: !!forceDisableFlip,
          videoConstraints: { facingMode:{ideal:'environment'}, width:{ideal: isDesktop?2560:1920}, height:{ideal:isDesktop?1440:1080}, advanced:[{focusMode:'continuous'}] }
        };
      }

      async function start(id){
        if (starting || running) return;
        starting = true;
        try{
          const ua = navigator.userAgent || navigator.vendor || '';
          const isIOS = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
          if (!qr) qr = new Html5Qrcode('reader',{verbose:false});
          if (isIOS) {
            try{
              const tmp = await navigator.mediaDevices.getUserMedia({audio:false,video:{facingMode:{ideal:'environment'}}});
              tmp.getTracks().forEach(t=>t.stop());
            }catch(_){ setStatus('Camera permission denied on iOS','text-danger'); starting=false; return; }
          }
          const cfgObj = cfg(isIOS ? 'ios':'default');
          const cameraConfig = id ? { deviceId:{ exact:id } } : { facingMode:{ ideal:'environment' } };

          await qr.start(
            cameraConfig,
            cfgObj,
            (decodedText)=>{
              if (navigator.vibrate) navigator.vibrate(40);
              setStatus('QR detected — opening…','text-success');
              const activityId = extractActivityIdFrom(decodedText);
              if (activityId) { goToCheckin(activityId); }
              else {
                setStatus('Not a poster check-in link','text-warning');
                setTimeout(()=>setStatus('Looking for a QR code…',''), 1200);
              }
            },
            (_e)=>{ /* silent */ }
          );

          running = true; setStatus('Looking for a QR code…','');
          const vid = document.getElementById('reader')?.querySelector('video');
          if (vid) {
            vid.setAttribute('playsinline','true');
            vid.setAttribute('webkit-playsinline','true');
            vid.setAttribute('muted',''); vid.muted = true;
            try { vid.play && vid.play(); } catch(_){}
          }
          resizeReader();
          window.addEventListener('resize', resizeReader);
          window.addEventListener('orientationchange', resizeReader);
        }catch(err){ setStatus('Start failed — check permissions','text-danger'); }
        finally{ starting=false; }
      }

      async function stop(){
        if (!qr || !running || stopping) return;
        stopping = true;
        try{ await qr.stop(); }catch(_){}
        running=false; stopping=false; setStatus('Scanner stopped','');
        window.removeEventListener('resize', resizeReader);
        window.removeEventListener('orientationchange', resizeReader);
      }

      // Wire modal lifecycle
      $(document).on('shown.bs.modal', '#studentScanModal', async function(){
        forceDisableFlip = false;
        const tFlip = document.getElementById('toggleDisableFlip');
        if (tFlip) { tFlip.checked = false; tFlip.onchange = ()=> (forceDisableFlip = !!tFlip.checked); }

        renderModeButtons(); // init the mode buttons

        await enumerateCameras();
        const camSel = document.getElementById('cameraSelect');
        await start(camSel && camSel.value);
      });

      $(document).on('hidden.bs.modal', '#studentScanModal', async function(){ await stop(); });

      // Toolbar buttons inside modal
      $(document).on('click','#btnStart', async function(){ const camSel = document.getElementById('cameraSelect'); if (!devicesLoaded) await enumerateCameras(); await start(camSel && camSel.value); });
      $(document).on('click','#btnStop', async function(){ await stop(); });
      $(document).on('change','#cameraSelect', async function(){ await stop(); await start(this.value); });

      // Upload-to-scan
      $(document).on('click','#btnUpload', function(){ document.getElementById('qrFileInput').click(); });
      $(document).on('change','#qrFileInput', async function(e){
        const file = e.target.files && e.target.files[0]; if (!file) return;
        try{
          if (!qr) qr = new Html5Qrcode('reader',{verbose:false});
          if (running) await stop();
          const txt = await qr.scanFile(file, false);
          const activityId = extractActivityIdFrom(txt);
          if (activityId) { goToCheckin(activityId); }
          else setStatus('Not a poster check-in link','text-warning');
        }catch(_){ setStatus('Couldn’t read that image','text-danger'); }
        finally{ this.value=''; }
      });

      // HTTPS hint for iOS/Safari
      (function(){
        const ua = navigator.userAgent || navigator.vendor || '';
        const isIOS = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        const isSafari = /^((?!chrome|android).)*safari/i.test(ua);
        if ((isIOS || isSafari) && location.protocol !== 'https:' && location.hostname !== 'localhost') {
          const warn = document.createElement('div');
          warn.className = 'alert alert-warning my-2';
          warn.innerHTML = '<b>iOS camera requires HTTPS or localhost.</b> Please open this page over https://';
          document.getElementById('studentScanModal')?.querySelector('.modal-body')?.prepend(warn);
        }
      })();
    })();
  </script>
</body>
</html>
