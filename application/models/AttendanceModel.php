<?php
class AttendanceModel extends CI_Model {
    private $table = 'cr_attendance';

    // $date is optional; $term/SY/Sem are used when provided
public function getBySubject($subjectCode, $section, $date = null, $term = null, $sy = null, $sem = null) {
    $this->db->select('a.*, sp.FirstName, sp.LastName')
             ->from("$this->table a")
             ->join('studeprofile sp', 'sp.StudentNumber = a.StudentNumber', 'left')
             ->where('a.SubjectCode', $subjectCode)
             ->where('a.Section', $section);

    if ($date) {
        $this->db->where('DATE(a.dateRecorded) =', date('Y-m-d', strtotime($date)));
    }
    if ($term) $this->db->where('a.term', $term);
    if ($sy)   $this->db->where('a.SY', $sy);
    if ($sem)  $this->db->where('a.Sem', $sem);

    return $this->db->get()->result();
}


  public function getEnrolledStudents($subjectCode, $section)
    {
        return $this->db->select('r.StudentNumber,
                                  sp.FirstName, sp.LastName,
                                  r.Course, r.Major, r.YearLevel,
                                  r.Description, r.LecUnit, r.LabUnit')
                        ->from('registration r')
                        ->join('studeprofile sp', 'sp.StudentNumber = r.StudentNumber', 'left')
                        ->where('r.SubjectCode', $subjectCode)
                        ->where('r.Section', $section)
                        ->group_by('r.StudentNumber')
                        ->order_by('sp.LastName, sp.FirstName')
                        ->get()->result();
    }

    // Example shown from your snippet; keep as-is if already working:
    // UNIQUE(StudentNumber, SubjectCode, Section, dateRecorded, term, SY, Sem)
    public function upsertDaily($data)
    {
        $where = [
            'StudentNumber' => $data['StudentNumber'],
            'SubjectCode'   => $data['SubjectCode'],
            'Section'       => $data['Section'],
            'dateRecorded'  => $data['dateRecorded'],
            'term'          => $data['term'],
            'SY'            => $data['SY'],
            'Sem'           => $data['Sem'],
        ];
        $exists = $this->db->get_where($this->table, $where)->row();

        if ($exists) {
            $this->db->where('attID', $exists->attID)
                     ->update($this->table, [
                        'attendance'   => $data['attendance'],
                        'timeRecorded' => $data['timeRecorded'],
                        'IDNumber'     => $data['IDNumber'],
                     ]);
            return $exists->attID;
        } else {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    public function delete($attID) {
        return $this->db->where('attID', $attID)->delete($this->table);
    }












    public function termSummary($subjectCode, $section, $term, $sy, $sem)
{
    // All attendance rows in term
    $rows = $this->db->select('StudentNumber, attendance, dateRecorded')
        ->from('cr_attendance')
        ->where([
            'SubjectCode' => $subjectCode,
            'Section'     => $section,
            'term'        => $term,
            'SY'          => $sy,
            'Sem'         => $sem,
        ])->get()->result();

    // possible = number of distinct dates in term
    $dates = [];
    foreach ($rows as $r) $dates[$r->dateRecorded] = true;
    $possible = count($dates);

    // scoring: Present=1, Excused=1, Late=0.5, Absent=0
    $points = [];
    foreach ($rows as $r) {
        $sn = $r->StudentNumber;
        if (!isset($points[$sn])) $points[$sn] = 0.0;
        $code = (int)$r->attendance; // 0=Absent,1=Present,2=Late,3=Excused
        $points[$sn] += ($code===1 || $code===3) ? 1.0 : (($code===2) ? 0.5 : 0.0);
    }

    return ['possible'=>$possible, 'points'=>$points];
}



public function getStudentsBySubjectSyId($subjectCode, $sy, $idNumber)
{
    return $this->db->select('r.StudentNumber, sp.FirstName, sp.LastName')
        ->from('registration r')
        ->join('studeprofile sp', 'sp.StudentNumber = r.StudentNumber', 'left')
        ->where('r.SubjectCode', $subjectCode)
        ->where('r.SY', $sy)
        ->where('r.IDNumber', $idNumber)
        ->group_by('r.StudentNumber')
        ->order_by('sp.LastName, sp.FirstName')
        ->get()->result();
}

}
