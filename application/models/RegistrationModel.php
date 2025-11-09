<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegistrationModel extends CI_Model
{
    // Distinct sections offered for a Course + YearLevel (optional filters)
    public function getSectionsByCourseYear($course = null, $yearLevel = null, $sy = null, $sem = null)
    {
        $this->db->distinct();
        $this->db->select('Section');
        $this->db->from('registration');

        if (!empty($course))    $this->db->where('Course', $course);
        if (!empty($yearLevel)) $this->db->where('YearLevel', $yearLevel);
        if (!empty($sy))        $this->db->where('SY', $sy);
        if (!empty($sem))       $this->db->where('Sem', $sem);

        $this->db->where("TRIM(IFNULL(Section,'')) <>", "");
        $this->db->order_by('Section', 'ASC');
        return $this->db->get()->result_array();
    }

    // Save registration row (now including YearLevel & Section)
    public function saveRegistration($payload)
    {
        // $payload includes: StudentNumber, Course, Major, YearLevel, Section, SY, Sem, etc.
        $this->db->insert('registration', $payload);
        return $this->db->insert_id();
    }
}
