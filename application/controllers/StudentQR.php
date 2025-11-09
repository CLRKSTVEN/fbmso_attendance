<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class StudentQR extends CI_Controller {
public function __construct(){
parent::__construct();
$this->load->database();
$this->load->helper(['url']);
$this->load->library(['session']);
$this->load->model('Student_qr_model', 'StudentQR');
}


public function myqr(){
$student = (string)$this->session->userdata('student_number');
if (!$student) show_error('Unauthorized', 401);
$row = $this->StudentQR->get_or_create($student);
$data = [
'student_number' => $student,
'token' => $row->qr_token,
'status' => $row->status,
];
$this->load->view('student_qr_show', $data);
}
}