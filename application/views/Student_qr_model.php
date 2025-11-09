<?php
class Student_qr_model extends CI_Model {

    public function get_or_issue($student_number) {
        $row = $this->db->where('student_number', $student_number)
                        ->get('student_qr')->row();
        if ($row) {
            $row->token = $row->qr_token;
            return $row;
        }
        $token = bin2hex(random_bytes(16));
        $now   = date('Y-m-d H:i:s');

        $this->db->insert('student_qr', [
            'student_number' => $student_number,
            'qr_token'       => $token,
            'status'         => 'active',
            'issued_at'      => $now,
        ]);

        return (object)[
            'student_number' => $student_number,
            'qr_token'       => $token,
            'token'          => $token, 
            'status'         => 'active',
            'issued_at'      => $now,
        ];
    }

    // public function get_by_token($token) {
    //     $row = $this->db->where('qr_token', $token)
    //                     ->where('status', 'active')
    //                     ->get('student_qr')->row();
    //     if ($row) $row->token = $row->qr_token;
    //     return $row;
    // }
     public function get_active($student_number) {
        $row = $this->db->where('student_number', $student_number)
                        ->where('status', 'active')
                        ->get('student_qr')->row();
        if ($row) $row->token = $row->qr_token;
        return $row ?: null;
    }

    public function get_by_token($token) {
        $row = $this->db->where('qr_token', $token)
                        ->where('status', 'active')
                        ->get('student_qr')->row();
        if ($row) $row->token = $row->qr_token;
        return $row;
    }
}
