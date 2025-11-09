<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('includes/top-nav-bar.php'); ?>
        <!-- Left Sidebar Start -->
        <?php include('includes/sidebar.php'); ?>
        <!-- End Sidebar -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                              <h4 class="mb-0">
    👨‍🎓 Students in Year Level: <strong><?= $yearLevel ?> | <?= $courseDescription; ?> | <?= $sy . ', ' . $sem; ?></strong>
</h4>

                            </div>
                            <hr style="border:0; height:2px; background:linear-gradient(to right, #4285F4 60%, #FBBC05 80%, #34A853 100%); border-radius:1px; margin:20px 0;" />
                        </div>
                    </div>

                    <!-- Student Table -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-people-fill text-primary"></i> Student List</h5>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-hover dt-responsive nowrap" style="width: 100%;">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>Student Number</th>
                                            <th>Full Name</th>
                                            <th>Section</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $stud): ?>
                                            <tr>
                                                <td><?= $stud->StudentNumber ?></td>
                                                <td><?= strtoupper($stud->LastName . ', ' . $stud->FirstName . ' ' . $stud->MiddleName) ?></td>
                                                <td><?= $stud->Section ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
                  <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-4">Section Summary</h4>

                                    <table class="table table-striped table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th style="text-align:left">Section</th>
                                                <th style="text-align:center">Counts</th>
                                                <th style="text-align:center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data2 as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row->Section; ?></td>
                                                    <td style="text-align:center"><?php echo $row->sectionCounts; ?></td>
                                                    <td style="text-align:center">
                                                      <a href="<?= base_url('Page/masterlistBySectionFiltered?section=' . urlencode($row->Section) 
    . '&course=' . urlencode($courseDescription) 
    . '&major=' . urlencode($major ?? '')); ?>" 
   class="btn btn-primary btn-sm">
    View List
</a>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end card -->

            </div> <!-- content -->
        </div> <!-- content-page -->

        <!-- Footer Start -->
        <?php include('includes/footer.php'); ?>
        <!-- Footer End -->

    </div> <!-- END wrapper -->

    <!-- Theme Customizer -->
    <?php include('includes/themecustomizer.php'); ?>

    <!-- Scripts -->
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    <!-- DataTables Scripts -->
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

</body>
</html>
