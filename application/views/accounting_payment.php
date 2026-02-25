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
                    <?php
                    $flashSuccess = $this->session->flashdata('success');
                    $flashDanger  = $this->session->flashdata('danger');
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="page-title mb-0">Payment Entry</h4>
                                    <?php if (!empty($semester) || !empty($sy)): ?>
                                        <div class="mt-1">
                                            <span class="badge badge-info">
                                                Context: <?= htmlspecialchars(trim(($semester ?: 'N/A') . ' | ' . ($sy ?: 'N/A')), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">
                                        <i class="mdi mdi-plus-circle"></i> Add Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($flashSuccess)): ?>
                        <div class="alert alert-success" role="alert"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($flashDanger)): ?>
                        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($flashDanger, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>

                    <!-- RECENT PAYMENTS BELOW -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="header-title mb-0">Recent Student Payments</h5>
                                        <small class="text-muted">Latest entries</small>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="recentPaymentsTable" class="table table-bordered table-sm dt-responsive nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>O.R.</th>
                                                    <th>Student</th>
                                                    <th>Description</th>
                                                    <th class="text-right">Amount</th>
                                                    <th>Type</th>
                                                    <th>Receipt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_payments as $row): ?>
                                                    <?php
                                                    $studentName = trim((string)($row->LastName ?? ''));
                                                    if ($studentName !== '') $studentName .= ', ';
                                                    $studentName .= trim((string)(($row->FirstName ?? '') . ' ' . ($row->MiddleName ?? '')));
                                                    if (trim($studentName) === '') $studentName = (string)($row->StudentNumber ?? '');
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars((string)($row->PDate ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->ORNumber ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->description ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td class="text-right"><?= number_format((float)($row->Amount ?? 0), 2); ?></td>
                                                        <td><?= htmlspecialchars((string)($row->PaymentType ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary print-receipt-btn"
                                                                data-id="<?= (int)($row->ID ?? 0); ?>"
                                                                data-ornumber="<?= htmlspecialchars((string)($row->ORNumber ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-date="<?= htmlspecialchars((string)($row->PDate ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-studentname="<?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-studentno="<?= htmlspecialchars((string)($row->StudentNumber ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-description="<?= htmlspecialchars((string)($row->description ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-paymenttype="<?= htmlspecialchars((string)($row->PaymentType ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-amount="<?= htmlspecialchars((string)($row->Amount ?? 0), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-sem="<?= htmlspecialchars((string)($row->Sem ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-sy="<?= htmlspecialchars((string)($row->SY ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                                data-cashier="<?= htmlspecialchars((string)($row->Cashier ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                                                <i class="mdi mdi-printer"></i> Print
                                                            </button>
                                                        </td>
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

    <!-- PAYMENT MODAL -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="<?= base_url('Accounting/Payment'); ?>" id="paymentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">
                            <i class="mdi mdi-cash-plus"></i> Add Student Payment
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="Sem" value="<?= htmlspecialchars((string)$semester, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="SY" value="<?= htmlspecialchars((string)$sy, ENT_QUOTES, 'UTF-8'); ?>">

                        <!-- keep description for controller, but hidden -->
                        <input type="hidden" name="description" id="descriptionHidden" value="">

                        <div class="form-group">
                            <label for="studentSelect">Student</label>
                            <select class="form-control" id="studentSelect" name="StudentNumber" required>
                                <option value="">Select student...</option>
                                <?php foreach ($students as $student): ?>
                                    <?php
                                    $studentNo = trim((string)($student->StudentNumber ?? ''));
                                    $ln = trim((string)($student->LastName ?? ($student->LName ?? '')));
                                    $fn = trim((string)($student->FirstName ?? ($student->FName ?? '')));
                                    $mn = trim((string)($student->MiddleName ?? ($student->MName ?? '')));

                                    $name = trim(($ln !== '' ? $ln . ', ' : '') . $fn . ($mn !== '' ? ' ' . $mn : ''));
                                    $optionText = ($name !== '') ? trim($studentNo . ' - ' . $name) : $studentNo;

                                    $course = trim((string)($student->Course ?? ''));
                                    $major = trim((string)($student->Major ?? ''));
                                    $yearLevel = trim((string)($student->YearLevel ?? ''));
                                    ?>
                                    <option
                                        value="<?= htmlspecialchars($studentNo, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-course="<?= htmlspecialchars($course, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-major="<?= htmlspecialchars($major, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-yearlevel="<?= htmlspecialchars($yearLevel, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= htmlspecialchars($optionText, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="orNumber">O.R. Number</label>
                                <input type="text" class="form-control" id="orNumber" name="ORNumber"
                                    value="<?= htmlspecialchars((string)$next_or_number, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="paymentDate">Payment Date</label>
                                <input type="date" class="form-control" id="paymentDate" name="PDate"
                                    value="<?= htmlspecialchars((string)$default_payment_date, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="feeTemplate">Description <span class="text-danger">*</span></label>
                            <select class="form-control" id="feeTemplate" required>
                                <option value="">Select fee...</option>
                            </select>
                            <div class="mt-2">
                                <span class="badge badge-light" id="selectedFeeBadge" style="display:none;"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" name="Amount" min="0" step="0.01" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="paymentType">Payment Type</label>
                                <select class="form-control" id="paymentType" name="PaymentType">
                                    <option value="Cash" selected>Cash</option>
                                    <option value="Check">Check</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                        </div>

                        <!-- CHECK FIELDS (hidden unless Check) -->
                        <div id="checkFields" style="display:none;">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="checkNumber">Check Number</label>
                                    <input type="text" class="form-control" id="checkNumber" name="CheckNumber">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank">Bank</label>
                                    <input type="text" class="form-control" id="bank" name="Bank">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-2 mb-0" id="feeWarning" style="display:none;">
                            Please select a <b>Fee Template</b>. Description will be based on it.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PRINT RECEIPT MODAL -->
    <div class="modal fade" id="printReceiptModal" tabindex="-1" role="dialog" aria-labelledby="printReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document" style="max-width: 5.5in;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printReceiptModalLabel">Receipt Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="receipt-preview">
                        <div class="receipt-body">
                            <!-- Header -->
                            <div class="receipt-header">
                                <div class="school-name" id="prevSchoolName"></div>
                                <div class="receipt-title">OFFICIAL RECEIPT</div>
                            </div>

                            <div class="receipt-divider"></div>

                            <!-- OR Number -->
                            <div class="receipt-ornumber">
                                <span class="label">OR NO:</span>
                                <span class="value" id="prevORNumber"></span>
                            </div>

                            <!-- Details -->
                            <div class="receipt-details">
                                <div class="detail-row">
                                    <span class="label">Date:</span>
                                    <span class="value" id="prevDate"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Received From:</span>
                                    <span class="value" id="prevStudentName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Student No:</span>
                                    <span class="value" id="prevStudentNo"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Description:</span>
                                    <span class="value" id="prevDescription"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Type:</span>
                                    <span class="value" id="prevPaymentType"></span>
                                </div>
                            </div>

                            <div class="receipt-divider"></div>

                            <!-- Amount -->
                            <div class="receipt-amount">
                                <span class="label">AMOUNT:</span>
                                <span class="value" id="prevAmount"></span>
                            </div>

                            <div class="receipt-divider"></div>

                            <!-- Signature -->
                            <div class="receipt-signature">
                                <div class="sig-line"></div>
                                <div class="sig-label" id="prevCashier"></div>
                                <div class="sig-pos">Cashier</div>
                            </div>

                            <div class="receipt-footer" id="prevFooter"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="mdi mdi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var baseUrl = <?= json_encode(base_url()); ?>;
            var semester = <?= json_encode((string)$semester); ?>;
            var schoolName = <?= json_encode((string)($settings->SchoolName ?? 'School')); ?>;

            function setCheckVisibility() {
                var isCheck = $('#paymentType').val() === 'Check';
                $('#checkFields').toggle(isCheck);

                // clear if not check
                if (!isCheck) {
                    $('#checkNumber').val('');
                    $('#bank').val('');
                }
            }

            function populateFeeTemplates(items) {
                var $feeTemplate = $('#feeTemplate');
                $feeTemplate.empty();
                $feeTemplate.append($('<option>', {
                    value: '',
                    text: 'Select fee.'
                }));

                (items || []).forEach(function(item) {
                    var label = (item.description || '') + ' - ' + Number(item.amount || 0).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    var $opt = $('<option>', {
                        value: item.feesid,
                        text: label
                    });
                    $opt.attr('data-description', item.description || '');
                    $opt.attr('data-amount', item.amount || 0);
                    $feeTemplate.append($opt);
                });

                $feeTemplate.trigger('change.select2');
            }

            function loadFeeTemplates() {
                // reset selected fee info
                $('#descriptionHidden').val('');
                $('#selectedFeeBadge').hide().text('');
                $('#feeTemplate').val('').trigger('change');

                $.getJSON(baseUrl + 'Accounting/ajaxFees')
                    .done(function(resp) {
                        populateFeeTemplates((resp && resp.fees) ? resp.fees : []);
                    })
                    .fail(function() {
                        populateFeeTemplates([]);
                    });
            }

            function applyFeeSelection() {
                var option = $('#feeTemplate').find('option:selected');
                var desc = option.attr('data-description') || '';
                var amount = option.attr('data-amount') || '';

                if (desc) {
                    $('#descriptionHidden').val(desc);
                    $('#selectedFeeBadge').text('Selected: ' + desc).show();
                    $('#feeWarning').hide();
                } else {
                    $('#descriptionHidden').val('');
                    $('#selectedFeeBadge').hide().text('');
                }

                if (amount !== '') {
                    $('#amount').val(Number(amount).toFixed(2));
                }
            }

            function validateBeforeSubmit() {
                var student = ($('#studentSelect').val() || '').trim();
                var feeDesc = ($('#descriptionHidden').val() || '').trim();
                var feeId = ($('#feeTemplate').val() || '').trim();

                if (!student) return false;

                if (!feeId || !feeDesc) {
                    $('#feeWarning').show();
                    return false;
                }
                $('#feeWarning').hide();
                return true;
            }

            $(function() {
                // DataTable
                $('#recentPaymentsTable').DataTable({
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ]
                });

                $('#paymentModal').on('shown.bs.modal', function() {
                    $('#studentSelect').select2({
                        width: '100%',
                        dropdownParent: $('#paymentModal')
                    });
                    $('#feeTemplate').select2({
                        width: '100%',
                        dropdownParent: $('#paymentModal')
                    });

                    setCheckVisibility();
                    loadFeeTemplates();
                });

                $('#paymentModal').on('hidden.bs.modal', function() {
                    $('#paymentForm')[0].reset();

                    // restore OR/date values after reset
                    $('#orNumber').val(<?= json_encode((string)$next_or_number); ?>);
                    $('#paymentDate').val(<?= json_encode((string)$default_payment_date); ?>);

                    // destroy select2 to avoid double init
                    if ($.fn.select2) {
                        try {
                            $('#studentSelect').select2('destroy');
                        } catch (e) {}
                        try {
                            $('#feeTemplate').select2('destroy');
                        } catch (e) {}
                    }

                    populateFeeTemplates([]);
                    $('#descriptionHidden').val('');
                    $('#selectedFeeBadge').hide().text('');
                    $('#feeWarning').hide();
                    $('#checkFields').hide();
                });

                $(document).on('change', '#paymentType', setCheckVisibility);
                $(document).on('change', '#feeTemplate', applyFeeSelection);

                // Handle print receipt button
                $(document).on('click', '.print-receipt-btn', function() {
                    var data = {
                        ornumber: $(this).data('ornumber'),
                        date: $(this).data('date'),
                        studentname: $(this).data('studentname'),
                        studentno: $(this).data('studentno'),
                        description: $(this).data('description'),
                        paymenttype: $(this).data('paymenttype'),
                        amount: $(this).data('amount'),
                        sem: $(this).data('sem'),
                        sy: $(this).data('sy'),
                        cashier: $(this).data('cashier')
                    };

                    // Format date
                    if (data.date && data.date !== '0000-00-00') {
                        var dateObj = new Date(data.date);
                        data.date = dateObj.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }

                    // Populate receipt
                    $('#prevSchoolName').text(schoolName);
                    $('#prevORNumber').text(data.ornumber);
                    $('#prevDate').text(data.date);
                    $('#prevStudentName').text(data.studentname);
                    $('#prevStudentNo').text(data.studentno);
                    $('#prevDescription').text(data.description);
                    $('#prevPaymentType').text(data.paymenttype);
                    $('#prevAmount').text('PHP ' + Number(data.amount || 0).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#prevCashier').text(data.cashier);
                    $('#prevFooter').text('Sem/SY: ' + data.sem + ' ' + data.sy);

                    // Show modal
                    $('#printReceiptModal').modal('show');
                });

                $('#paymentForm').on('submit', function(e) {
                    if (!validateBeforeSubmit()) {
                        e.preventDefault();
                    }
                });
            });
        })();

        function printReceipt() {
            var printContent = $('.receipt-preview').html();
            var printWindow = window.open('', '', 'height=500,width=500');
            printWindow.document.write('<html><head><title>Receipt</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                body { font-family: "Courier New", monospace; margin: 0; padding: 0.2in; }
                .receipt-body { font-size: 11px; line-height: 1.3; }
                .receipt-header { text-align: center; margin-bottom: 0.15in; }
                .school-name { font-size: 12px; font-weight: bold; margin-bottom: 2px; }
                .receipt-title { font-size: 11px; font-weight: bold; letter-spacing: 1px; }
                .receipt-divider { border-top: 1px dashed #333; margin: 0.1in 0; }
                .receipt-ornumber { display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 0.1in; font-size: 12px; }
                .receipt-details { margin-bottom: 0.1in; }
                .detail-row { display: flex; justify-content: space-between; margin-bottom: 3px; padding: 0 2px; }
                .detail-row .label { font-weight: bold; width: 35%; flex-shrink: 0; }
                .detail-row .value { text-align: right; word-wrap: break-word; }
                .receipt-amount { display: flex; justify-content: space-between; font-weight: bold; font-size: 12px; padding: 0.1in 0; margin-bottom: 0.1in; }
                .receipt-signature { text-align: center; margin-top: 0.2in; margin-bottom: 0.1in; }
                .sig-line { border-top: 1px solid #333; width: 60%; margin: 0 auto 2px; height: 20px; }
                .sig-label { font-size: 9px; font-weight: bold; }
                .sig-pos { font-size: 8px; margin-top: 1px; }
                .receipt-footer { text-align: center; font-size: 9px; border-top: 1px dashed #333; padding-top: 3px; margin-top: 0.1in; }
            `);
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

    <style>
        .receipt-preview {
            max-width: 5.5in;
            margin: 0 auto;
            background: #fff;
        }

        .receipt-body {
            padding: 0.3in;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.3;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 0.15in;
        }

        .school-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .receipt-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .receipt-divider {
            border-top: 1px dashed #333;
            margin: 0.1in 0;
        }

        .receipt-ornumber {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 0.1in;
            font-size: 12px;
        }

        .receipt-details {
            margin-bottom: 0.1in;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            padding: 0 2px;
        }

        .detail-row .label {
            font-weight: bold;
            width: 35%;
            flex-shrink: 0;
        }

        .detail-row .value {
            text-align: right;
            word-wrap: break-word;
            word-break: break-word;
        }

        .receipt-amount {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            padding: 0.1in 0;
            margin-bottom: 0.1in;
        }

        .receipt-signature {
            text-align: center;
            margin-top: 0.2in;
            margin-bottom: 0.1in;
        }

        .sig-line {
            border-top: 1px solid #333;
            width: 60%;
            margin: 0 auto 2px;
            height: 20px;
        }

        .sig-label {
            font-size: 9px;
            font-weight: bold;
        }

        .sig-pos {
            font-size: 8px;
            margin-top: 1px;
        }

        .receipt-footer {
            text-align: center;
            font-size: 9px;
            border-top: 1px dashed #333;
            padding-top: 3px;
            margin-top: 0.1in;
        }
    </style>

</html>