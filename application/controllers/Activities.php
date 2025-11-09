<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activities extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session']);
        $this->load->model('Activities_model', 'ActivitiesModel');
        $this->load->model('AuditLogModel');
    }

    public function index()
    {
        $data['rows']       = $this->ActivitiesModel->list_all();
        usort($data['rows'], function ($a, $b) {
            $lastNameA = strtolower($a->LastName ?? '');
            $firstNameA = strtolower($a->FirstName ?? '');
            $lastNameB = strtolower($b->LastName ?? '');
            $firstNameB = strtolower($b->FirstName ?? '');

            if ($lastNameA === $lastNameB) {
                return $firstNameA <=> $firstNameB;
            }

            return $lastNameA <=> $lastNameB;
        });
        $data['posterMode'] = ($this->get_global_poster_mode() === 'on');
        $this->load->view('activities_list', $data);
    }

    public function create()
    {
        $programs = $this->db->select('DISTINCT CourseDescription AS name', false)
            ->from('course_table')->order_by('name')->get()->result();
        if (!$programs) {
            $programs = [(object)['name' => 'YFD'], (object)['name' => 'YES-O'], (object)['name' => 'BKDC']];
        }
        $majors = $this->db->select('DISTINCT Major', false)
            ->from('course_table')
            ->where('Major <>', '')
            ->order_by('Major')
            ->get()->result();

        $data = [
            'mode'     => 'create',
            'row'      => null,
            'programs' => $programs,
            'majors'   => $majors,
            'action'   => site_url('activities/store'),
            'btn_text' => 'Save Activity',
            'titlebar' => 'Create Co-Curricular Activity — QR Attendance',
        ];
        $this->load->view('activities_create', $data);
    }


    public function delete($id)
    {
        $id = (int)$id;

        // (Optional) capture a lightweight old snapshot before delete
        $row = $this->ActivitiesModel->find($id);
        $old = null;
        if ($row) {
            $old = [
                'title'       => $row->title ?? null,
                'description' => $row->description ?? null,
                'location'    => $row->location ?? null,
                'program'     => $row->program ?? null,
                'start_at'    => $row->start_at ?? null,
                'end_at'      => $row->end_at ?? null,
            ];
        }

        $ok = $this->ActivitiesModel->delete($id);

        // AUDIT
        $this->AuditLogModel->write(
            'delete',
            'Activities',
            'activities',
            $id,
            $old,
            null,
            $ok ? 1 : 0,
            $ok ? 'Deleted activity' : 'Failed to delete activity'
        );

        redirect('activities');
    }


    public function poster($activity_id)
    {
        $activity_id = (int)$activity_id;
        $activity = $this->ActivitiesModel->find($activity_id);
        if (!$activity) {
            show_404();
            return;
        }

        // Build the check-in path once
        $path = 'attendance/checkin/' . $activity_id;
        $xfProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
        $xfHost  = $_SERVER['HTTP_X_FORWARDED_HOST']  ?? null;

        $scheme = $xfProto ?: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
        $host   = $xfHost  ?: ($_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST));
        $origin = $scheme . '://' . $host;

        $path   = 'attendance/checkin/' . $activity_id;
        $data['checkin_url']  = rtrim($origin, '/') . '/' . ltrim($path, '/');
        $data['checkin_path'] = $path;


        $data['activity'] = $activity;
        $this->load->view('activity_qr_poster', $data);
    }



    public function store()
    {
        $title         = trim((string)$this->input->post('title', TRUE));
        $description   = trim((string)$this->input->post('description', TRUE));
        $location      = trim((string)$this->input->post('location', TRUE));
        $activity_date = trim((string)$this->input->post('activity_date', TRUE)); // YYYY-MM-DD
        $start_time    = trim((string)$this->input->post('start_time', TRUE));    // HH:MM
        $end_time      = trim((string)$this->input->post('end_time', TRUE));      // HH:MM

        // Program (supports custom entered by the view js)
        $program       = (string)$this->input->post('program', TRUE);
        $program_custom = trim((string)$this->input->post('program_custom', TRUE));
        if ($program === '__custom__') $program = $program_custom;

        // Sessions meta (JSON from hidden field)
        $meta_json = (string)$this->input->post('meta'); // may be empty or "{}"
        $meta      = null;
        if ($meta_json !== '') {
            $decoded = json_decode($meta_json, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $meta = json_encode($decoded, JSON_UNESCAPED_UNICODE);
            }
        }

        // Validation
        if ($title === '' || $activity_date === '') {
            $this->session->set_flashdata('error', 'Title and Date are required.');
            return redirect('activities/create');
        }
        if ((string)$this->input->post('program', TRUE) === '__custom__' && $program === '') {
            $this->session->set_flashdata('error', 'Please type a Program name.');
            return redirect('activities/create');
        }

        // Build start_at / end_at (all-day allowed by leaving times empty)
        $start_at = $activity_date . ' ' . ($start_time !== '' ? $start_time : '00:00:00');
        $end_at   = ($end_time !== '') ? ($activity_date . ' ' . $end_time) : null;

        $settingsID = 1;  // FK to settings

        $data = [
            'settingsID'  => $settingsID,
            'title'       => $title,
            'description' => $description ?: null,
            'location'    => $location ?: null,
            'program'     => $program !== '' ? $program : '',
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'is_open'     => 1,
            'meta'        => $meta,                        // <- save sessions JSON
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $ok = $this->db->insert('activities', $data);
        if (!$ok) {
            $err = $this->db->error();
            log_message('error', 'Activities insert failed: ' . $err['code'] . ' - ' . $err['message']);

            // AUDIT: create failed
            $this->AuditLogModel->write(
                'create',
                'Activities',
                'activities',
                null,
                null,
                $data,
                0,
                'Failed to create activity',
                ['db_error' => $err]
            );

            $this->session->set_flashdata('error', 'Failed to save activity. ' . $err['message']);
            return redirect('activities/create');
        }

        // AUDIT: create success
        $newId = $this->db->insert_id();
        $this->AuditLogModel->write(
            'create',
            'Activities',
            'activities',
            $newId,
            null,
            [
                'title'       => $title,
                'description' => $description ?: null,
                'location'    => $location ?: null,
                'program'     => $program !== '' ? $program : '',
                'start_at'    => $start_at,
                'end_at'      => $end_at,
                'meta'        => $meta
            ],
            1,
            'Created activity'
        );

        $this->session->set_flashdata('success', 'Activity created.');
        return redirect('activities');
    }
    public function edit($id)
    {
        $id  = (int)$id;
        $row = $this->ActivitiesModel->find($id);
        if (!$row) {
            show_404();
            return;
        }

        $programs = $this->db->select('DISTINCT CourseDescription AS name', false)
            ->from('course_table')->order_by('name')->get()->result();
        if (!$programs) {
            $programs = [(object)['name' => 'YFD'], (object)['name' => 'YES-O'], (object)['name' => 'BKDC']];
        }
        $majors = $this->db->select('DISTINCT Major', false)
            ->from('course_table')
            ->where('Major <>', '')
            ->order_by('Major')
            ->get()->result();

        $data = [
            'mode'     => 'edit',
            'row'      => $row,
            'programs' => $programs,
            'majors'   => $majors,
            'action'   => site_url('activities/' . $row->activity_id . '/update'),
            'btn_text' => 'Update Activity',
            'titlebar' => 'Edit Co-Curricular Activity — QR Attendance',
        ];
        $this->load->view('activities_create', $data);
    }

    public function update($id)
    {
        $id  = (int)$id;
        $row = $this->ActivitiesModel->find($id);
        if (!$row) {
            show_404();
            return;
        }

        $title         = trim((string)$this->input->post('title', TRUE));
        $description   = trim((string)$this->input->post('description', TRUE));
        $location      = trim((string)$this->input->post('location', TRUE));
        $activity_date = trim((string)$this->input->post('activity_date', TRUE));
        $start_time    = trim((string)$this->input->post('start_time', TRUE));
        $end_time      = trim((string)$this->input->post('end_time', TRUE));

        $program       = (string)$this->input->post('program', TRUE);
        $program_custom = trim((string)$this->input->post('program_custom', TRUE));
        if ($program === '__custom__') $program = $program_custom;

        $meta_json = (string)$this->input->post('meta');
        $meta      = null;
        if ($meta_json !== '') {
            $decoded = json_decode($meta_json, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $meta = json_encode($decoded, JSON_UNESCAPED_UNICODE);
            }
        }

        if ($title === '' || $activity_date === '') {
            $this->session->set_flashdata('error', 'Title and Date are required.');
            return redirect('activities/' . $id . '/edit');
        }
        if ((string)$this->input->post('program', TRUE) === '__custom__' && $program === '') {
            $this->session->set_flashdata('error', 'Please type a Program name.');
            return redirect('activities/' . $id . '/edit');
        }

        $start_at = $activity_date . ' ' . ($start_time !== '' ? $start_time : '00:00:00');
        $end_at   = ($end_time !== '') ? ($activity_date . ' ' . $end_time) : null;

        $data = [
            'title'       => $title,
            'description' => $description ?: null,
            'location'    => $location ?: null,
            'program'     => $program !== '' ? $program : '',
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'meta'        => $meta,                       // <- update sessions JSON
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $ok = $this->db->where('activity_id', $id)->update('activities', $data);

        // Build old/new snapshots (lightweight)
        $old = [
            'title'       => $row->title ?? null,
            'description' => $row->description ?? null,
            'location'    => $row->location ?? null,
            'program'     => $row->program ?? null,
            'start_at'    => $row->start_at ?? null,
            'end_at'      => $row->end_at ?? null,
            'meta'        => $row->meta ?? null,
        ];

        // AUDIT
        $this->AuditLogModel->write(
            'update',
            'Activities',
            'activities',
            $id,
            $old,
            [
                'title'       => $title,
                'description' => $description ?: null,
                'location'    => $location ?: null,
                'program'     => $program !== '' ? $program : '',
                'start_at'    => $start_at,
                'end_at'      => $end_at,
                'meta'        => $meta
            ],
            $ok ? 1 : 0,
            $ok ? 'Updated activity' : 'Failed to update activity'
        );

        if (!$ok) {
            $err = $this->db->error();
            $this->session->set_flashdata('error', 'Failed to update activity. ' . $err['message']);
            return redirect('activities/' . $id . '/edit');
        }

        $this->session->set_flashdata('success', 'Activity updated.');
        return redirect('activities');
    }
    public function majors_by_program()
    {
        $programRaw = trim((string)$this->input->get('program', TRUE));
        $this->output->set_content_type('application/json');

        if ($programRaw === '') {
            return $this->output->set_output(json_encode(['ok' => true, 'majors' => []]));
        }

        // If UI sent "Description — Major", strip the right side
        $base = $programRaw;
        if (preg_match('/\s+[—-]\s+/u', $programRaw)) {
            $parts = preg_split('/\s+[—-]\s+/u', $programRaw, 2);
            $base  = trim($parts[0]);
        }

        $q = $this->db->select('DISTINCT TRIM(Major) AS major', false)
            ->from('course_table')
            ->group_start()
            ->where('TRIM(CourseDescription) =', $base)
            ->or_where('TRIM(CourseCode) =',       $base)
            ->or_where('TRIM(CourseDescription) =', $programRaw)
            ->or_where('TRIM(CourseCode) =',        $programRaw)
            ->group_end()
            ->where("(Major IS NOT NULL AND TRIM(Major) <> '')", null, false)
            ->order_by('major', 'ASC')
            ->get();

        $majors = [];
        foreach ($q->result() as $r) {
            $majors[] = (string)$r->major;
        }

        return $this->output->set_output(json_encode(['ok' => true, 'majors' => $majors]));
    }

    private function _poster_flag_path(): string
    {
        return APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'qr_poster_mode.flag';
    }

    private function get_global_poster_mode(): string
    {
        $path = $this->_poster_flag_path();
        if (is_file($path)) {
            $v = strtolower(trim(@file_get_contents($path)));
            return ($v === 'on') ? 'on' : 'off';
        }
        return 'off';
    }

    private function write_global_poster_mode(string $mode): void
    {
        $path = $this->_poster_flag_path();
        @file_put_contents($path, ($mode === 'on' ? 'on' : 'off'), LOCK_EX);
    }

    public function set_mode($mode = 'off')
    {
        if ($this->session->userdata('level') !== 'Admin') {
            show_error('Forbidden', 403);
        }
        $mode = strtolower((string)$mode) === 'on' ? 'on' : 'off';
        $this->write_global_poster_mode($mode);
        $this->session->set_userdata('qr_poster_mode', $mode);
        // AUDIT: poster mode toggle
        $this->AuditLogModel->write(
            'update',
            'Activities',
            'settings_flag',
            'qr_poster_mode',
            null,
            ['mode' => $mode],
            1,
            'Toggled QR poster mode'
        );

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['ok' => true, 'mode' => $mode]));
        }
        $ref = $this->input->server('HTTP_REFERER') ?: site_url('activities');
        redirect($ref);
    }
    public function fill_missing()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        // Allow staff roles (adjust as needed)
        $level = (string)$this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Instructor', 'Registrar', 'Accounting'], true)) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'message' => 'Forbidden']));
        }

        $activity_id = (int)$this->input->post('activity_id');
        $by      = trim((string)$this->input->post('checked_in_by'));
        $remarks = trim((string)$this->input->post('remarks'));

        if (!$activity_id) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'message' => 'Missing activity_id']));
        }

        $result = ['updated_by' => 0, 'updated_remarks' => 0];

        if ($by !== '') {
            $this->db->set('checked_in_by', $by)
                ->where('activity_id', $activity_id)
                ->group_start()
                ->where('checked_in_by IS NULL', null, false)
                ->or_where('checked_in_by', '')
                ->group_end()
                ->update('activity_attendance');
            $result['updated_by'] = $this->db->affected_rows();
        }

        if ($remarks !== '') {
            $this->db->set('remarks', $remarks)
                ->where('activity_id', $activity_id)
                ->group_start()
                ->where('remarks IS NULL', null, false)
                ->or_where('remarks', '')
                ->group_end()
                ->update('activity_attendance');
            $result['updated_remarks'] = $this->db->affected_rows();
        }

        // AUDIT: bulk update attendance “fill missing”
        $this->AuditLogModel->write(
            'update',
            'Activities',
            'activity_attendance',
            (string)$activity_id,
            null,
            ['checked_in_by_set' => $result['updated_by'], 'remarks_set' => $result['updated_remarks']],
            1,
            'Filled missing attendance fields',
            ['checked_in_by' => $by, 'remarks' => $remarks]
        );

        return $this->output->set_content_type('application/json')
            ->set_output(json_encode(['ok' => true] + $result));
    }
}
