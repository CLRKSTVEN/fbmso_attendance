<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php'); ?>
    <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

<body>
<div id="wrapper">

    <?php include('includes/top-nav-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <div class="content-page">
        <div class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-title-box">
                            <h4 class="page-title">UPDATE COURSE</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item">
                                        <a href="#"><span class="badge badge-purple mb-3">
                                            Currently login to <b>SY <?= $this->session->userdata('sy'); ?> <?= $this->session->userdata('semester'); ?>
                                        </span></b></a>
                                    </li>
                                </ol>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="border:0; height:2px; background:linear-gradient(to right, #4285F4 60%, #FBBC05 80%, #34A853 100%); border-radius:1px; margin:20px 0;" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $this->session->flashdata('msg'); ?>
                        <div class="card">
                            <div class="card-body table-responsive">

                                <?php if ($data): ?>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <div class="col-lg-12">
                                                <label>Course Code. <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="CourseCode" required value="<?= $data->CourseCode; ?>">
                                            </div>

                                            <div class="col-lg-12 mt-2">
                                                <label>Course <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="CourseDescription" required value="<?= $data->CourseDescription; ?>">
                                            </div>

                                            <div class="col-lg-12 mt-2">
                                                <label>Major</label>
                                                <input type="text" class="form-control" name="Major" value="<?= $data->Major; ?>">
                                            </div>

                                            <div class="col-lg-12 mt-2">
                                                <label>Duration</label>
                                                <select name="Duration" class="form-control" id="Duration">
                                                    <option value="">Duration</option>
                                                    <option value="1 Year" <?= ($data->Duration === '1 Year') ? 'selected' : ''; ?>>1 Year</option>
                                                    <option value="2 Years" <?= ($data->Duration === '2 Years') ? 'selected' : ''; ?>>2 Years</option>
                                                    <option value="3 Years" <?= ($data->Duration === '3 Years') ? 'selected' : ''; ?>>3 Years</option>
                                                    <option value="4 Years" <?= ($data->Duration === '4 Years') ? 'selected' : ''; ?>>4 Years</option>
                                                    <option value="5 Years" <?= ($data->Duration === '5 Years') ? 'selected' : ''; ?>>5 Years</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-12 mt-2">
                                                <label>Recognition No./Permit No.</label>
                                                <input type="text" class="form-control" name="recogNo" value="<?= $data->recogNo; ?>">
                                            </div>

                                            <div class="col-lg-12 mt-2">
                                                <label>Series Year</label>
                                                <input type="text" class="form-control" name="SeriesYear" value="<?= $data->SeriesYear; ?>">
                                            </div>

                                            <!-- <div class="col-lg-12 mt-2">
                                                <label>Program Head</label>
                                                <select class="form-control select2" name="IDNumber" id="IDNumber">
                                                    <option value="">Select Program Head</option>
                                                    <?php foreach ($staff as $s): ?>
                                                        <option value="<?= $s->IDNumber ?>" <?= ($s->IDNumber == $data->IDNumber) ? 'selected' : ''; ?>>
                                                            <?= $s->FirstName . ' ' . $s->MiddleName . ' ' . $s->LastName ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> -->

                                        </div>

                                        <div class="modal-footer">
                                            <input type="submit" name="update" value="Save Data" class="btn btn-primary waves-effect waves-light" />
                                        </div>
                                    </form>

                                <?php else: ?>
                                    <p class="text-danger">No course found for the given ID.</p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include('includes/footer.php'); ?>

    </div>

    <?php include('includes/themecustomizer.php'); ?>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>
    <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>

     <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>
</html>
