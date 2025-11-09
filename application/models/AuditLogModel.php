<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuditLogModel extends CI_Model
{
    public function write($action, $module = null, $table = null, $recordPk = null, $old = null, $new = null, $succeeded = 1, $description = null, $extra = null)
    {
        // Pull what we can from session
        $username = (string) $this->session->userdata('username');
        $fname    = (string) $this->session->userdata('fname');
        $mname    = (string) $this->session->userdata('mname');
        $lname    = (string) $this->session->userdata('lname');
        $fullName = trim($lname . ', ' . $fname . ($mname ? (' ' . $mname) : ''));
        if ($fullName === ',' || $fullName === '') $fullName = null;

        $payload = [
            'action'     => $action,
            'module'     => $module,
            'table_name' => $table,
            'record_pk'  => is_scalar($recordPk) ? (string)$recordPk : null,
            'succeeded'  => (int)!!$succeeded,

            'username'   => $username ?: null,
            'full_name'  => $fullName,
            'user_id'    => (string) $this->session->userdata('IDNumber') ?: null,

            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr((string) $this->input->user_agent(), 0, 255),

            'description' => $description,
            'event_time' => date('Y-m-d H:i:s')
        ];

        // JSON fields (accept arrays/objects or JSON strings)
        $jsonify = function ($val) {
            if ($val === null || $val === '') return null;
            if (is_string($val)) {
                // if looks like JSON, store as-is; else wrap into {"_": "..."} for safety
                $trim = ltrim($val);
                if ($trim !== '' && ($trim[0] === '{' || $trim[0] === '[')) return $val;
                return json_encode(['_' => $val], JSON_UNESCAPED_UNICODE);
            }
            return json_encode($val, JSON_UNESCAPED_UNICODE);
        };

        $payload['old_values'] = $jsonify($old);
        $payload['new_values'] = $jsonify($new);
        $payload['extra']      = $jsonify($extra);

        return $this->db->insert('audit_logs', $payload);
    }
}
