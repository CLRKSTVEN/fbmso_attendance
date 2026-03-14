<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>
<link href="<?= base_url(); ?>assets/libs/summernote/summernote-bs4.css" rel="stylesheet" type="text/css" />

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <?php
                    $openPanel = (string) ($open_panel ?? '');
                    $openHistory = ($openPanel === 'history');
                    $currentTargetType = (string) ($target_type ?? 'all');
                    if (!in_array($currentTargetType, ['all', 'year', 'section', 'individual'], true)) {
                        $currentTargetType = 'all';
                    }
                    $selectedStudentOptionText = '';
                    if (!empty($selected_student_option)) {
                        $optionStudentNumber = trim((string) ($selected_student_option->StudentNumber ?? ''));
                        $optionLastName = trim((string) ($selected_student_option->LastName ?? ''));
                        $optionFirstName = trim((string) ($selected_student_option->FirstName ?? ''));
                        $optionMiddleName = trim((string) ($selected_student_option->MiddleName ?? ''));
                        $optionYearLevel = trim((string) ($selected_student_option->YearLevel ?? ''));
                        $optionSection = trim((string) ($selected_student_option->Section ?? ''));

                        $optionName = trim($optionLastName . ', ' . $optionFirstName);
                        if ($optionMiddleName !== '') {
                            $optionName .= ' ' . strtoupper(substr($optionMiddleName, 0, 1)) . '.';
                        }
                        $optionName = trim($optionName, ', ');

                        $optionParts = [];
                        if ($optionStudentNumber !== '') {
                            $optionParts[] = $optionStudentNumber;
                        }
                        if ($optionName !== '') {
                            $optionParts[] = $optionName;
                        }

                        $optionMeta = trim($optionYearLevel . ($optionYearLevel !== '' && $optionSection !== '' ? ' - ' : '') . $optionSection);
                        if ($optionMeta !== '') {
                            $optionParts[] = $optionMeta;
                        }

                        $selectedStudentOptionText = implode(' - ', $optionParts);
                    }
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Mass Announcement Email &amp; SMS</h4>
                            </div>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('warning'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('danger')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('danger'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-12 d-flex flex-wrap">
                            <button type="button"
                                class="btn btn-outline-info mb-2"
                                data-toggle="collapse"
                                data-target="#announcementHistoryCollapse"
                                aria-expanded="<?= $openHistory ? 'true' : 'false'; ?>"
                                aria-controls="announcementHistoryCollapse">
                                View Announcements
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Send Announcement to Enrolled Students</h4>



                            <form method="post" action="<?= site_url('mass-announcement/send'); ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="target_type">Send To</label>
                                        <select name="target_type" id="target_type" class="form-control">
                                            <option value="all" <?= $currentTargetType === 'all' ? 'selected' : ''; ?>>All Enrolled Students</option>
                                            <option value="year" <?= $currentTargetType === 'year' ? 'selected' : ''; ?>>By Year Level</option>
                                            <option value="section" <?= $currentTargetType === 'section' ? 'selected' : ''; ?>>By Section</option>
                                            <option value="individual" <?= $currentTargetType === 'individual' ? 'selected' : ''; ?>>Individual Student</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3" id="year-level-group">
                                        <label for="year_level">Year Level</label>
                                        <select name="year_level" id="year_level" class="form-control">
                                            <option value="">Select Year Level</option>
                                            <?php foreach ((array) $year_levels as $level): ?>
                                                <option value="<?= html_escape($level); ?>" <?= ((string) $selected_year_level === (string) $level) ? 'selected' : ''; ?>>
                                                    <?= html_escape($level); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3" id="section-group">
                                        <label for="section">Section</label>
                                        <select name="section" id="section" class="form-control" data-selected="<?= html_escape((string) ($selected_section ?? '')); ?>">
                                            <option value="">Select Section</option>
                                            <?php foreach ((array) $sections as $sec): ?>
                                                <option value="<?= html_escape($sec); ?>" <?= ((string) ($selected_section ?? '') === (string) $sec) ? 'selected' : ''; ?>>
                                                    <?= html_escape($sec); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 d-flex flex-column justify-content-center">
                                        <div class="custom-control custom-checkbox mt-4">
                                            <input type="checkbox" class="custom-control-input" id="include_parents" name="include_parents" value="1" <?= !empty($include_parents) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="include_parents">Include parent email.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6" id="student-group">
                                        <label for="student_number">Student (Search)</label>
                                        <select name="student_number" id="student_number" class="form-control">
                                            <?php if ((string) ($selected_student_number ?? '') !== ''): ?>
                                                <option value="<?= html_escape((string) $selected_student_number); ?>" selected>
                                                    <?= html_escape($selectedStudentOptionText !== '' ? $selectedStudentOptionText : (string) $selected_student_number); ?>
                                                </option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required maxlength="255">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mass-message">Message</label>
                                    <textarea id="mass-message" name="message" class="form-control" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" onclick="return confirm('Send this mass announcement now (email and GSM SMS)?');">
                                    Send Mass Announcement
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="collapse <?= $openHistory ? 'show' : ''; ?>" id="announcementHistoryCollapse">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Announcement History</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Subject</th>
                                                <th>Filter</th>
                                                <th>Recipients</th>
                                                <th>Sent/Failed</th>
                                                <th>Created By</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($announcement_history)): ?>
                                                <?php foreach ($announcement_history as $row): ?>
                                                    <?php
                                                    $messageHtml = (string) ($row->message ?? '');
                                                    $messageText = trim(strip_tags($messageHtml));
                                                    $targetLabel = trim((string) ($row->year_level ?? '')) !== '' ? (string) $row->year_level : 'All Enrolled Students';
                                                    $messagePreview = (strlen($messageText) > 70) ? (substr($messageText, 0, 67) . '...') : $messageText;
                                                    ?>
                                                    <tr>
                                                        <td><?= html_escape(date('M d, Y h:i A', strtotime((string) ($row->created_at ?? 'now')))); ?></td>
                                                        <td><?= html_escape((string) ($row->subject ?? '')); ?></td>
                                                        <td>
                                                            SY <?= html_escape((string) ($row->sy ?? '')); ?>
                                                            <br>
                                                            Sem <?= html_escape((string) ($row->semester ?? '')); ?>
                                                            <br>
                                                            Target <?= html_escape($targetLabel); ?>
                                                            <br>
                                                            Parents <?= !empty($row->include_parents) ? 'Yes' : 'No'; ?>
                                                        </td>
                                                        <td><?= (int) ($row->recipient_count ?? 0); ?></td>
                                                        <td><?= (int) ($row->sent_count ?? 0); ?> / <?= (int) ($row->failed_count ?? 0); ?></td>
                                                        <td><?= html_escape((string) ($row->created_by ?? '')); ?></td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary view-ann-msg"
                                                                data-toggle="modal"
                                                                data-target="#announcementMessageModal"
                                                                data-subject="<?= html_escape((string) ($row->subject ?? '')); ?>"
                                                                data-message="<?= html_escape($messageHtml); ?>">
                                                                View
                                                            </button>
                                                            <div class="small text-muted mt-1">
                                                                <?= html_escape($messagePreview); ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No sent announcements yet.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/summernote/summernote-bs4.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    <div class="modal fade" id="announcementMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="announcementMessageTitle">Announcement Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="announcementMessageBody"></div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            var $targetType = $('#target_type');
            var $yearLevel = $('#year_level');
            var $section = $('#section');
            var $student = $('#student_number');
            var $yearGroup = $('#year-level-group');
            var $sectionGroup = $('#section-group');
            var $studentGroup = $('#student-group');
            var sectionUrl = '<?= site_url('mass-announcement/sections'); ?>';
            var studentsUrl = '<?= site_url('mass-announcement/students'); ?>';

            $section.select2({
                width: '100%',
                placeholder: 'Select section',
                allowClear: true
            });

            $student.select2({
                width: '100%',
                placeholder: 'Search student number or name',
                allowClear: true,
                ajax: {
                    url: studentsUrl,
                    dataType: 'json',
                    delay: 200,
                    data: function(params) {
                        return {
                            q: params.term || '',
                            year_level: $yearLevel.val() || '',
                            section: $section.val() || ''
                        };
                    },
                    processResults: function(data) {
                        return data;
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            function renderTargetMode() {
                var mode = ($targetType.val() || 'all').toLowerCase();
                var useYear = (mode === 'year' || mode === 'section');
                var useSection = (mode === 'section');
                var useStudent = (mode === 'individual');

                $yearGroup.toggle(useYear);
                $sectionGroup.toggle(useSection);
                $studentGroup.toggle(useStudent);

                $yearLevel.prop('disabled', !useYear);
                $section.prop('disabled', !useSection);
                $student.prop('disabled', !useStudent);
                $yearLevel.prop('required', mode === 'year');
                $section.prop('required', useSection);
                $student.prop('required', useStudent);

                if (!useYear) {
                    $yearLevel.val('');
                }
                if (!useSection) {
                    $section.val('').trigger('change');
                }
                if (!useStudent) {
                    $student.val(null).trigger('change');
                }
            }

            function reloadSections(keepSelected) {
                var selected = keepSelected ? ($section.data('selected') || $section.val() || '') : '';

                $.getJSON(sectionUrl, {
                    year_level: $yearLevel.val() || ''
                }).done(function(data) {
                    var items = (data && data.results) ? data.results : [];
                    $section.empty().append('<option value=""></option>');
                    $.each(items, function(_, item) {
                        if (!item || typeof item.id === 'undefined') {
                            return;
                        }
                        var option = new Option(item.text, item.id, false, false);
                        $section.append(option);
                    });

                    if (selected !== '') {
                        $section.val(selected);
                    } else {
                        $section.val('');
                    }
                    $section.trigger('change');
                    $section.data('selected', '');
                }).fail(function() {
                    $section.empty().append('<option value=""></option>').trigger('change');
                });
            }

            $targetType.on('change', function() {
                renderTargetMode();
                if (($targetType.val() || '').toLowerCase() === 'section') {
                    reloadSections(true);
                }
            });

            $yearLevel.on('change', function() {
                if (($targetType.val() || '').toLowerCase() === 'section') {
                    reloadSections(false);
                }
            });

            renderTargetMode();
            if (($targetType.val() || '').toLowerCase() === 'section') {
                reloadSections(true);
            }

            $('#mass-message').summernote({
                height: 260,
                placeholder: 'Write your announcement message...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $(document).on('click', '.view-ann-msg', function() {
                var subject = $(this).data('subject') || 'Announcement Message';
                var message = $(this).data('message') || '';
                $('#announcementMessageTitle').text(subject);
                $('#announcementMessageBody').html(message);
            });
        });
    </script>
</body>

</html>