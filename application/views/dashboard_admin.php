<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<style>
  a.text-decoration-none:hover {
    text-decoration: none;
  }

  .kpi {
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
    box-shadow: 0 6px 18px rgba(36, 59, 83, .08);
    transition: transform .22s ease, box-shadow .22s ease
  }

  .kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(36, 59, 83, .14)
  }

  .kpi .card-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem
  }

  .kpi .count {
    font-size: 2.0rem;
    font-weight: 800;
    color: #1f2d3d;
    margin: 0;
    line-height: 1
  }

  .kpi .label {
    margin: .15rem 0 0;
    color: #546e7a;
    font-weight: 700;
    letter-spacing: .2px
  }

  .kpi .icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 28px
  }

  .kpi.blue .icon {
    background: rgba(37, 99, 235, .08);
    color: #2563eb
  }

  .kpi.pink .icon {
    background: rgba(236, 72, 153, .10);
    color: #ec4899
  }

  .kpi.purple .icon {
    background: rgba(139, 92, 246, .10);
    color: #8b5cf6
  }

  .kpi.cyan .icon {
    background: rgba(6, 182, 212, .10);
    color: #06b6d4
  }

  .kpi.primary .icon {
    background: rgba(59, 130, 246, .10);
    color: #3b82f6
  }

  .card.announcement-card {
    transition: transform .3s ease, box-shadow .3s ease, border .3s ease;
    border: 1px solid #dee2e6;
    border-radius: 6px
  }

  .card.announcement-card:hover {
    transform: scale(1.03);
    border: 2px solid #007bff;
    box-shadow: 0 8px 20px rgba(0, 123, 255, .2)
  }

  .card.reg-ann {
    border: 1px solid #dee2e6
  }

  .card.reg-ann .card-header {
    background: #17a2b8;
    color: #fff;
    padding: .9rem 1rem
  }

  .card.reg-ann .card-title {
    margin: 0;
    font-weight: 600
  }

  .ann-row {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.0rem;
    background: #fdfdfd;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .05);
    transition: transform .25s ease, box-shadow .25s ease;
    margin-bottom: 12px
  }

  .ann-row:hover {
    transform: scale(1.01);
    box-shadow: 0 6px 18px rgba(0, 0, 0, .1)
  }

  .ann-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: .25rem;
    border-left: 5px solid #007bff;
    padding-left: .5rem
  }

  .ann-meta {
    font-size: .9rem;
    color: #6c757d;
    margin-bottom: .5rem
  }

  .ann-actions a {
    font-weight: 600;
    color: #007bff;
    text-decoration: none
  }

  .ann-actions a:hover {
    text-decoration: underline
  }

  .modal-body img {
    max-width: 100%;
    height: auto;
    border-radius: 6px
  }

  #viewAnnouncementModal .modal-body {
    max-height: 75vh;
    overflow: auto
  }

  .ann-flex {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    flex-wrap: nowrap
  }

  .ann-text {
    flex: 1;
    font-size: 1rem;
    line-height: 1.6;
    max-height: 60vh;
    overflow: auto
  }

  .ann-aside {
    width: 38%;
    min-width: 260px
  }

  .ann-aside img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: contain
  }

  @media (max-width:768px) {
    .ann-flex {
      flex-direction: column
    }

    .ann-aside {
      width: 100%;
      min-width: 0
    }

    .ann-text {
      max-height: none
    }
  }

  .card .table th {
    font-weight: 700;
  }

  .card .table td,
  .card .table th {
    vertical-align: middle;
  }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
  }

  .page-title-box .page-title {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
    word-break: break-word;
    hyphens: auto;
    line-height: 1.25;
  }

  @media (max-width: 767.98px) {
    .page-title-box {
      display: block;
    }

    .page-title-right {
      float: none !important;
      margin-top: .5rem;
    }

    .page-title-right .breadcrumb,
    .page-title-right .badge {
      white-space: normal !important;
    }
  }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
  }

  @media (max-width: 767.98px) {
    .kpi-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    }

    .kpi-grid>a:nth-child(1) {
      order: 5;
    }

    .kpi .card-body {
      padding: .9rem;
    }

    .kpi .count {
      font-size: 1.6rem;
    }

    .kpi .icon {
      width: 44px;
      height: 44px;
      font-size: 22px;
      border-radius: 12px;
    }
  }

  @media (min-width: 768px) and (max-width: 899.98px) {
    .kpi-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  .card.enroll-card {
    border: 1px solid #dee2e6;
  }

  .card.enroll-card .card-header {
    background: #6f42c1;
    color: #fff;
    padding: .9rem 1rem;
  }

  .card.enroll-card .card-title {
    margin: 0;
    font-weight: 600;
  }

  .card.enroll-card .badge-term {
    background: #5a32a6;
  }

  .card.enroll-card .table thead th {
    background: #f8f9fc;
  }

  .enroll-split {
    display: flex;
    gap: 24px;
    align-items: flex-start;
  }

  .enroll-col {
    flex: 1 1 0;
    min-width: 260px;
  }

  @media (max-width: 991.98px) {
    .enroll-split {
      flex-direction: column;
      gap: 16px;
    }
  }

  @media (max-width: 767.98px) {
    .kpi-grid>a.kpi-span-2 {
      grid-column: 1 / -1;
      order: 99;
    }
  }
</style>

<body>
  <div id="wrapper">

    <?php include('includes/top-nav-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <div class="content-page">
      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">
              <div class="page-title-box">
                <h4 class="page-title">
                  <?php echo $data18[0]->SchoolName; ?><br />
                  <small class="text-muted"><?php echo $data18[0]->SchoolAddress; ?></small>
                </h4>

                <div class="page-title-right">
                  <ol class="breadcrumb p-0 m-0">
                    <li class="breadcrumb-item">
                      <span class="badge badge-purple mb-3">
                        Currently login to <b>SY <?php echo $this->session->userdata('sy'); ?> <?php echo $this->session->userdata('semester'); ?></b>
                      </span>
                    </li>
                  </ol>
                </div>

                <div class="clearfix"></div>
                <hr style="border:0;height:2px;background:linear-gradient(to right,#4285F4 60%,#FBBC05 80%,#34A853 100%);border-radius:1px;margin:20px 0;">
              </div>
            </div>
          </div>

          <?php
          $SP_count = (int)($data7[0]->StudeCount ?? 0);

          $yl1 = $data[0]  ?? null;
          $yl2 = $data1[0] ?? null;
          $yl3 = $data2[0] ?? null;
          $yl4 = $data3[0] ?? null;

          $yl1Count = (int)($yl1->StudeCount ?? 0);
          $yl2Count = (int)($yl2->StudeCount ?? 0);
          $yl3Count = (int)($yl3->StudeCount ?? 0);
          $yl4Count = (int)($yl4->StudeCount ?? 0);

          $yl1Level = $yl1->YearLevel ?? '1st Year';
          $yl2Level = $yl2->YearLevel ?? '2nd Year';
          $yl3Level = $yl3->YearLevel ?? '3rd Year';
          $yl4Level = $yl4->YearLevel ?? '4th Year';

          $sy   = $this->session->userdata('sy');
          $sem  = $this->session->userdata('semester');
          ?>
          <div class="kpi-grid">
            <a href="<?= base_url(); ?>Page/profileList" class="text-decoration-none kpi-span-2">
              <div class="card kpi blue">
                <div class="card-body">
                  <div>
                    <h2 class="count mb-1">
                      <span data-plugin="counterup"><?= number_format($SP_count); ?></span>
                    </h2>
                    <p class="label mb-0">Registered Students</p>
                  </div>
                  <div class="icon"><i class="mdi mdi-layers-plus"></i></div>
                </div>
              </div>
            </a>
            <a href="<?= base_url(); ?>Masterlist/byGradeYL?sy=<?= urlencode($sy) ?>&sem=<?= urlencode($sem) ?>&yearlevel=<?= urlencode($yl1Level) ?>" class="text-decoration-none">
              <div class="card kpi pink">
                <div class="card-body">
                  <div>
                    <h2 class="count mb-1"><span data-plugin="counterup"><?= number_format($yl1Count); ?></span></h2>
                    <p class="label mb-0">1st Year</p>
                  </div>
                  <div class="icon"><i class="mdi mdi-monitor-lock"></i></div>
                </div>
              </div>
            </a>
            <a href="<?= base_url(); ?>Masterlist/byGradeYL?sy=<?= urlencode($sy) ?>&sem=<?= urlencode($sem) ?>&yearlevel=<?= urlencode($yl2Level) ?>" class="text-decoration-none">
              <div class="card kpi purple">
                <div class="card-body">
                  <div>
                    <h2 class="count mb-1"><span data-plugin="counterup"><?= number_format($yl2Count); ?></span></h2>
                    <p class="label mb-0">2nd Year</p>
                  </div>
                  <div class="icon"><i class="mdi mdi-file-eye-outline"></i></div>
                </div>
              </div>
            </a>
            <a href="<?= base_url(); ?>Masterlist/byGradeYL?sy=<?= urlencode($sy) ?>&sem=<?= urlencode($sem) ?>&yearlevel=<?= urlencode($yl3Level) ?>" class="text-decoration-none">
              <div class="card kpi cyan">
                <div class="card-body">
                  <div>
                    <h2 class="count mb-1"><span data-plugin="counterup"><?= number_format($yl3Count); ?></span></h2>
                    <p class="label mb-0">3rd Year</p>
                  </div>
                  <div class="icon"><i class="mdi mdi-pen-lock"></i></div>
                </div>
              </div>
            </a>
            <a href="<?= base_url(); ?>Masterlist/byGradeYL?sy=<?= urlencode($sy) ?>&sem=<?= urlencode($sem) ?>&yearlevel=<?= urlencode($yl4Level) ?>" class="text-decoration-none">
              <div class="card kpi primary">
                <div class="card-body">
                  <div>
                    <h2 class="count mb-1"><span data-plugin="counterup"><?= number_format($yl4Count); ?></span></h2>
                    <p class="label mb-0">4th Year</p>
                  </div>
                  <div class="icon"><i class="mdi mdi-cast-education"></i></div>
                </div>
              </div>
            </a>
          </div>
          <div class="row mt-4">
            <div class="col-xl-12">
              <div class="card enroll-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <div>
                    <h5 class="card-title mb-0" style="color: white;">ENROLLMENT SUMMARY</h5>
                    <small class="badge badge-term badge-light text-white">
                      <?= htmlspecialchars($sem ?? $this->session->userdata('semester')); ?>,
                      SY <?= htmlspecialchars($sy ?? $this->session->userdata('sy')); ?>
                    </small>
                  </div>
                  <div class="card-widgets">
                    <a data-toggle="collapse" href="#enrollSummary" role="button" aria-expanded="true" aria-controls="enrollSummary">
                      <i class="mdi mdi-minus text-white"></i>
                    </a>
                  </div>
                </div>
                <div id="enrollSummary" class="collapse show">
                  <div class="card-body">
                    <div class="enroll-split">
                      <div class="enroll-col">
                        <h6 class="mb-3 text-uppercase text-muted">By Course</h6>
                        <div class="table-responsive">
                          <table class="table table-sm mb-0">
                            <thead>
                              <tr>
                                <th style="text-align:left">Course</th>
                                <th style="text-align:center">Counts</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($data8)) : ?>
                                <?php foreach ($data8 as $row): ?>
                                  <tr>
                                    <td style="text-align:left;">
                                      <?= htmlspecialchars($row->Course); ?>
                                      </a>
                                    </td>
                                    <td style="text-align:center">
                                      <button type="button" class="btn btn-primary btn-xs waves-effect waves-light">
                                        <?= number_format((int)$row->Counts); ?>
                                      </button>
                                      </a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="2" class="text-muted text-center">No data.</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- By Major
                      <div class="enroll-col">
                        <h6 class="mb-3 text-uppercase text-muted">By Major</h6>
                        <div class="table-responsive">
                          <table class="table table-sm mb-0">
                            <thead>
                              <tr>
                                <th style="text-align:left">Major</th>
                                <th style="text-align:center">Counts</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($majorCounts)) : ?>
                                <?php foreach ($majorCounts as $row): ?>
                                  <tr>
                                    <td style="text-align:left;"><?= htmlspecialchars($row->Major ?: 'Not Set'); ?></td>
                                    <td style="text-align:center">
                                      <button type="button" class="btn btn-success btn-xs waves-effect waves-light">
                                        <?= number_format((int)$row->Counts); ?>
                                      </button>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="2" class="text-muted text-center">No data.</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div> -->
                      <div class="enroll-col">
                        <h6 class="mb-3 text-uppercase text-muted">By Year Level</h6>
                        <div class="table-responsive">
                          <table class="table table-sm mb-0">
                            <thead>
                              <tr>
                                <th style="text-align:left">Year Level</th>
                                <th style="text-align:center">Counts</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($yearLevelCounts)) : ?>
                                <?php foreach ($yearLevelCounts as $row): ?>
                                  <tr>
                                    <td style="text-align:left;"><?= htmlspecialchars($row->YearLevel ?: 'Not Set'); ?></td>
                                    <td style="text-align:center">
                                      <button type="button" class="btn btn-info btn-xs waves-effect waves-light">
                                        <?= number_format((int)$row->Counts); ?>
                                      </button>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="2" class="text-muted text-center">No data.</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="enroll-col">
                        <h6 class="mb-3 text-uppercase text-muted">By Section</h6>
                        <?php if (!empty($this->input->get('course')) || !empty($this->input->get('major'))): ?>
                          <div class="mb-2">
                            <?php if ($this->input->get('course')): ?>
                              <span class="badge badge-info">Course: <?= htmlspecialchars($this->input->get('course')); ?></span>
                            <?php endif; ?>
                            <?php if ($this->input->get('major')): ?>
                              <span class="badge badge-secondary">Major: <?= htmlspecialchars($this->input->get('major')); ?></span>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                          <table class="table table-sm mb-0">
                            <thead>
                              <tr>
                                <th style="text-align:left">Section</th>
                                <th style="text-align:center">Enrollees</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($sectionCounts)) : ?>
                                <?php foreach ($sectionCounts as $row): ?>
                                  <tr>
                                    <td style="text-align:left;"><?= htmlspecialchars($row->Section ?: 'Not Set'); ?></td>
                                    <td style="text-align:center">
                                      <button type="button" class="btn btn-warning btn-xs waves-effect waves-light">
                                        <?= number_format((int)$row->Counts); ?>
                                      </button>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="2" class="text-muted text-center">No data.</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <!-- 
            <div class="enroll-col">
              <h6 class="mb-3 text-uppercase text-muted">By Sex</h6>
              <div class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead>
                    <tr>
                      <th style="text-align:left">Sex</th>
                      <th style="text-align:center">Counts</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($data9)) : ?>
                      <?php foreach ($data9 as $row): ?>
                        <tr>
                          <td style="text-align:left;"><?= htmlspecialchars($row->Sex); ?></td>
                          <td style="text-align:center">
                              <button type="button" class="btn btn-success btn-xs waves-effect waves-light">
                                <?= number_format((int)$row->Counts); ?>
                              </button>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="2" class="text-muted text-center">No data.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div> -->

                    </div>
                  </div>
                </div>

              </div>


              <div class="row mt-4">
                <div class="col-xl-12">
                  <div class="card reg-ann">
                    <div class="card-header">
                      <div class="card-widgets">
                        <a data-toggle="collapse" href="#adminAnnouncements" role="button" aria-expanded="true" aria-controls="adminAnnouncements">
                          <i class="mdi mdi-minus text-white"></i>
                        </a>
                      </div>
                      <h5 class="card-title mb-0" style="color: white;">ANNOUNCEMENT</h5>
                    </div>

                    <div id="adminAnnouncements" class="collapse show">
                      <div class="card-body">
                        <?php if (empty($announcements)): ?>
                          <div class="text-muted">No announcements.</div>
                        <?php else: ?>
                          <?php $i = 0;
                          foreach ($announcements as $row): $i++;
                            $modalID  = 'annView' . $i;
                            $title    = $row->title ?? 'Announcement';
                            $message  = $row->message ?? $row->description ?? '';
                            $posted   = !empty($row->datePosted) ? date('F d, Y', strtotime($row->datePosted)) : '';
                            $audience = $row->audience ?? 'All';
                            $imageURL = !empty($row->image) ? base_url('upload/announcements/' . $row->image) : '';
                          ?>
                            <div class="ann-row">
                              <?php if ($imageURL): ?>
                                <div class="row mb-2">
                                  <div class="col-md-3 text-center">
                                    <img src="<?= $imageURL; ?>" class="img-fluid rounded" style="max-height:100px;" alt="Preview Image">
                                  </div>
                                  <div class="col-md-9">
                                    <div class="ann-title"><i class="mdi mdi-bullhorn-outline mr-1 text-primary"></i><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="ann-meta">Posted on <?= $posted; ?> • Audience: <?= htmlspecialchars($audience, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="ann-actions"><a href="#" data-toggle="modal" data-target="#<?= $modalID; ?>">View Details</a></div>
                                  </div>
                                </div>
                              <?php else: ?>
                                <div class="ann-title"><i class="mdi mdi-bullhorn-outline mr-1 text-primary"></i><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="ann-meta">Posted on <?= $posted; ?> • Audience: <?= htmlspecialchars($audience, ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="ann-actions"><a href="#" data-toggle="modal" data-target="#<?= $modalID; ?>">View Details</a></div>
                              <?php endif; ?>
                            </div>

                            <div class="modal fade" id="<?= $modalID; ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modalID; ?>Label" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="<?= $modalID; ?>Label"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="ann-flex">
                                      <div class="ann-text" style="white-space:pre-wrap;"><?= $message; ?></div>
                                      <?php if ($imageURL): ?>
                                        <aside class="ann-aside">
                                          <img src="<?= $imageURL; ?>" alt="Announcement Image">
                                          <div class="text-right mt-2">
                                            <a href="<?= $imageURL; ?>" class="btn btn-outline-info btn-sm" download>
                                              <i class="mdi mdi-download"></i> Download Image
                                            </a>
                                          </div>
                                        </aside>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <?php include('includes/footer.php'); ?>
    </div>
  </div>

  <?php include('includes/themecustomizer.php'); ?>
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
  <script>
    $('#viewAnnouncementModal').on('show.bs.modal', function(e) {
      var t = $(e.relatedTarget);
      var title = t.data('title') || '';
      var message = t.data('message') || '';
      var img = t.data('image') || '';
      var hasImg = t.data('hasimage') === 1 || t.data('hasimage') === '1';

      $('#vamTitle').text(title);
      $('#vamMessage').html(message);

      if (hasImg && img) {
        $('#vamImage').attr('src', img);
        $('#vamImageWrap').show();
      } else {
        $('#vamImage').attr('src', '');
        $('#vamImageWrap').hide();
      }
    });
  </script>
</body>

</html>