<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.php'); ?>

<body>
    <?php
    $flashSuccess = $this->session->flashdata('success');
    $flashDanger  = $this->session->flashdata('danger');

    $schoolName = trim((string)($settings->SchoolName ?? 'School Records Management System'));
    $schoolAddr = trim((string)($settings->SchoolAddress ?? ''));
    $telNo      = trim((string)($settings->telNo ?? ''));

    $lastName   = trim((string)($payment->LastName ?? ''));
    $firstName  = trim((string)($payment->FirstName ?? ''));
    $middleName = trim((string)($payment->MiddleName ?? ''));
    $studentName = trim(($lastName !== '' ? $lastName . ', ' : '') . $firstName . ($middleName !== '' ? ' ' . $middleName : ''));
    if ($studentName === '') {
        $studentName = trim((string)$payment->StudentNumber);
    }

    $paidAt = trim((string)$payment->PDate);
    if ($paidAt !== '' && $paidAt !== '0000-00-00') {
        $paidAt = date('F d, Y', strtotime($paidAt));
    }

    $createdTime = trim((string)($payment->pTime ?? ''));
    $cashierName = trim((string)($payment->Cashier ?? ''));
    $cashierPos  = trim((string)($settings->cashierPosition ?? 'Cashier'));

    $letterhead = trim((string)($settings->letterhead_web ?? ''));
    $amountValue = (float)($payment->Amount ?? 0);
    ?>

    <div class="container-fluid py-4">
        <?php if (!empty($flashSuccess)): ?>
            <div class="alert alert-success no-print" role="alert"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if (!empty($flashDanger)): ?>
            <div class="alert alert-danger no-print" role="alert"><?= htmlspecialchars($flashDanger, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="<?= base_url('Accounting/Payment'); ?>" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Back to Payment
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="mdi mdi-printer"></i> Print Receipt
            </button>
        </div>

        <div class="card receipt-card mx-auto">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <?php if ($letterhead !== ''): ?>
                        <img src="<?= base_url('upload/banners/' . rawurlencode($letterhead)); ?>" alt="Letterhead" class="img-fluid mb-2" style="max-height: 120px;">
                    <?php endif; ?>
                    <h4 class="mb-1"><?= htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8'); ?></h4>
                    <?php if ($schoolAddr !== ''): ?><div><?= htmlspecialchars($schoolAddr, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                    <?php if ($telNo !== ''): ?><div>Contact: <?= htmlspecialchars($telNo, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                    <div>
                        <h5 class="mb-1">Official Receipt</h5>
                        <div><strong>OR No.:</strong> <?= htmlspecialchars((string)$payment->ORNumber, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div><strong>Date:</strong> <?= htmlspecialchars($paidAt, ENT_QUOTES, 'UTF-8'); ?><?= $createdTime !== '' ? ' ' . htmlspecialchars($createdTime, ENT_QUOTES, 'UTF-8') : ''; ?></div>
                    </div>
                    <div class="text-right mt-2 mt-md-0">
                        <div><strong>Sem/SY:</strong> <?= htmlspecialchars(trim((string)$payment->Sem . ' ' . (string)$payment->SY), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div><strong>Student No.:</strong> <?= htmlspecialchars((string)$payment->StudentNumber, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>

                <table class="table table-bordered mb-3">
                    <tr>
                        <th width="30%">Received From</th>
                        <td><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?= htmlspecialchars((string)$payment->description, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Type</th>
                        <td><?= htmlspecialchars((string)$payment->PaymentType, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php if (!empty($payment->refNo)): ?>
                        <tr>
                            <th>Reference No.</th>
                            <td><?= htmlspecialchars((string)$payment->refNo, ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($payment->CheckNumber) || !empty($payment->Bank)): ?>
                        <tr>
                            <th>Check Details</th>
                            <td>
                                <?php if (!empty($payment->CheckNumber)): ?>Check No: <?= htmlspecialchars((string)$payment->CheckNumber, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?>
                                <?php if (!empty($payment->Bank)): ?><?= !empty($payment->CheckNumber) ? ' | ' : ''; ?>Bank: <?= htmlspecialchars((string)$payment->Bank, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Amount</th>
                        <td><strong>PHP <?= number_format($amountValue, 2); ?></strong></td>
                    </tr>
                </table>

                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="text-muted small">Received by</div>
                        <div class="signature-line mt-4"></div>
                        <div class="font-weight-bold mt-1"><?= htmlspecialchars($cashierName, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="text-muted"><?= htmlspecialchars($cashierPos, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <div class="col-md-6 text-md-right mt-4 mt-md-0">
                        <div class="text-muted small">Payor Signature</div>
                        <div class="signature-line mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer_plugins.php'); ?>

    <style>
        .receipt-card {
            max-width: 900px;
            border: 1px solid #dfe6ee;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 100%;
        }

        @media print {
            .no-print,
            .topbar,
            .left-side-menu,
            .footer,
            .right-bar,
            .page-title-box,
            #wrapper > .content-page > .content > .container-fluid > .alert {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .receipt-card {
                border: 0 !important;
                box-shadow: none !important;
                margin: 0 auto !important;
            }
        }
    </style>

    <?php if (!empty($auto_print)): ?>
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 350);
            });
        </script>
    <?php endif; ?>
</body>

</html>
