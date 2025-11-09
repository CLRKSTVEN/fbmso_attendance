<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SignupModel extends CI_Model
{
    // Treat Feb 29 birthdays as Feb 28 on non-leap years
    private function leap_cond($col = 'birthDate')
    {
        return "(
            DATE_FORMAT($col, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')
            OR (
                MONTH($col) = 2 AND DAY($col) = 29
                AND MONTH(CURDATE()) = 2 AND DAY(CURDATE()) = 28
                AND NOT (
                    (YEAR(CURDATE()) % 400 = 0)
                    OR (YEAR(CURDATE()) % 4 = 0 AND YEAR(CURDATE()) % 100 != 0)
                )
            )
        )";
    }

    private function base_select()
    {
        return "
            signupID,
            StudentNumber,
            FirstName, MiddleName, LastName, nameExtn,
            Sex,
            birthDate,
            age, /* kept if you already compute it in table; we also recompute */
            Course3, yearLevel, section,
            imagePath,
            TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) AS AgeNow
        ";
    }

    // ---------- Today ----------
    public function birthdays_today()
    {
        $this->db->select($this->base_select(), false)
            ->from('studentsignup')
            ->where("birthDate IS NOT NULL AND birthDate <> '0000-00-00'", null, false)
            ->where($this->leap_cond(), null, false);

        // Optional: filter only active/enrolled if your Status field carries that value
        // $this->db->where('Status', 'Enrolled');

        $this->db->order_by('LastName')->order_by('FirstName');
        return $this->db->get()->result();
    }

    // ---------- This month ----------
    public function birthdays_this_month()
    {
        $this->db->select($this->base_select(), false)
            ->from('studentsignup')
            ->where("birthDate IS NOT NULL AND birthDate <> '0000-00-00'", null, false)
            ->where("MONTH(birthDate) = MONTH(CURDATE())", null, false);

        // Optional status filter
        // $this->db->where('Status', 'Enrolled');

        $this->db->order_by('DAY(birthDate) ASC')
            ->order_by('LastName ASC')
            ->order_by('FirstName ASC');

        return $this->db->get()->result();
    }
}
