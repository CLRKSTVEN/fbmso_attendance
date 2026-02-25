<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="page-title mb-0"><?= htmlspecialchars((string)$report_title, ENT_QUOTES, 'UTF-8'); ?></h4>

                                <div class="d-flex">
                                    <button type="button" class="btn btn-secondary mr-2" data-toggle="modal" data-target="#monthlyModal">
                                        <i class="mdi mdi-calendar-month-outline"></i> Monthly View
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#yearlyModal">
                                        <i class="mdi mdi-calendar-range-outline"></i> Yearly View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date range + stats -->
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <div class="card mb-0">
                                <div class="card-body py-3">
                                    <form method="get" action="<?= base_url('Accounting/collectionReport'); ?>" class="form-row align-items-end">
                                        <div class="col-md-4 mb-2">
                                            <label for="from" class="mb-1">From</label>
                                            <input type="date" id="from" name="from" class="form-control"
                                                value="<?= htmlspecialchars((string)$from, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="to" class="mb-1">To</label>
                                            <input type="date" id="to" name="to" class="form-control"
                                                value="<?= htmlspecialchars((string)$to, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="col-md-4 mb-2 text-right">
                                            <button class="btn btn-primary btn-block" type="submit">
                                                <i class="mdi mdi-filter-outline"></i> Apply Date Range
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card mb-0">
                                        <div class="card-body py-3">
                                            <h6 class="text-muted mb-1">Transactions</h6>
                                            <h4 class="mb-0"><?= (int)$total_count; ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card mb-0">
                                        <div class="card-body py-3">
                                            <h6 class="text-muted mb-1">Total Collection</h6>
                                            <h4 class="mb-0">₱<?= number_format((float)$total_amount, 2); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="collectionTable" class="table table-bordered table-sm dt-responsive nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>O.R.</th>
                                                    <th>Student No.</th>
                                                    <th>Student</th>
                                                    <th>Description</th>
                                                    <th>Payment Type</th>
                                                    <th>Sem/SY</th>
                                                    <th class="text-right">Amount</th>
                                                    <th>Cashier</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($rows as $row): ?>
                                                    <?php
                                                    $studentName = trim((string)($row->StudentName ?? ''));
                                                    if ($studentName === ',' || $studentName === '') {
                                                        $studentName = (string)($row->StudentNumber ?? '');
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars((string)($row->PDate ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->ORNumber ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->StudentNumber ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->description ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->PaymentType ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars(trim((string)($row->Sem ?? '') . ' ' . (string)($row->SY ?? '')), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="text-right"><?= number_format((float)($row->Amount ?? 0), 2); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->Cashier ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
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

    <?php include('includes/footer_plugins.php'); ?>

    <!-- MONTHLY MODAL -->
    <div class="modal fade" id="monthlyModal" tabindex="-1" role="dialog" aria-labelledby="monthlyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="get" action="<?= base_url('Accounting/collectionMonthly'); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="monthlyModalLabel">
                            <i class="mdi mdi-calendar-month-outline"></i> Monthly View
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="mb-1">Year</label>
                                <input type="number" class="form-control" name="year" value="<?= date('Y'); ?>" min="2000" max="2100" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="mb-1">Month</label>
                                <input type="number" class="form-control" name="month" value="<?= date('m'); ?>" min="1" max="12" required>
                            </div>
                        </div>
                        <small class="text-muted">Choose a year and month to view summary/collection for that month.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-secondary">View Monthly</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- YEARLY MODAL -->
    <div class="modal fade" id="yearlyModal" tabindex="-1" role="dialog" aria-labelledby="yearlyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="get" action="<?= base_url('Accounting/collectionYear'); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="yearlyModalLabel">
                            <i class="mdi mdi-calendar-range-outline"></i> Yearly View
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="mb-1">Year</label>
                            <input type="number" class="form-control" name="year" value="<?= date('Y'); ?>" min="2000" max="2100" required>
                        </div>
                        <small class="text-muted">Choose a year to view the yearly collection report.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-secondary">View Yearly</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#collectionTable').DataTable({
                pageLength: 20,
                order: [
                    [0, 'desc'],
                    [1, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'print']
            });
        });
    </script>
</body>

</html>