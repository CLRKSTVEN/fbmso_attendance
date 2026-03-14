<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MassAnnouncementModel extends CI_Model
{
    public function getActiveTerm()
    {
        $row = $this->db->select('active_sy, active_sem')
            ->limit(1)
            ->get('o_srms_settings')
            ->row();

        return [
            'sy' => trim((string) ($row->active_sy ?? '')),
            'semester' => trim((string) ($row->active_sem ?? '')),
        ];
    }

    public function countStudents($sy, $semester)
    {
        return (int) $this->baseRecipientQuery($sy, $semester)
            ->count_all_results();
    }

    public function countStudentsWithEmail($sy, $semester)
    {
        return (int) $this->baseRecipientQuery($sy, $semester)
            ->where("TRIM(COALESCE(NULLIF(sp.email, ''), NULLIF(su.email, ''))) <> ''", null, false)
            ->count_all_results();
    }

    public function getYearLevels($sy, $semester)
    {
        $rows = $this->baseRecipientQuery($sy, $semester)
            ->select('DISTINCT ss.YearLevel AS year_level', false)
            ->order_by('ss.YearLevel', 'ASC')
            ->get()
            ->result();

        $levels = [];
        foreach ($rows as $row) {
            $level = trim((string) ($row->year_level ?? ''));
            if ($level !== '') {
                $levels[] = $level;
            }
        }

        usort($levels, [$this, 'compareYearLevels']);

        return array_values(array_unique($levels));
    }

    public function getSections($sy, $semester, $yearLevel = '')
    {
        $query = $this->baseRecipientQuery($sy, $semester)
            ->select('DISTINCT ss.Section AS section', false)
            ->where("TRIM(COALESCE(ss.Section, '')) <> ''", null, false);

        $yearLevel = trim((string) $yearLevel);
        if ($yearLevel !== '') {
            $query->where('ss.YearLevel', $yearLevel);
        }

        $rows = $query->order_by('ss.Section', 'ASC')
            ->get()
            ->result();

        $sections = [];
        foreach ($rows as $row) {
            $section = trim((string) ($row->section ?? ''));
            if ($section !== '') {
                $sections[] = $section;
            }
        }

        natcasesort($sections);

        return array_values(array_unique($sections));
    }

    public function searchStudents($sy, $semester, $search, $yearLevel = '', $section = '', $limit = 20)
    {
        $search = trim((string) $search);
        $query = $this->baseRecipientQuery($sy, $semester)
            ->select($this->studentSelectSql(), false);

        $this->applyOptionalTargetFilters($query, $yearLevel, $section);

        if ($search !== '') {
            $query->group_start()
                ->like('ss.StudentNumber', $search)
                ->or_like('sp.LastName', $search)
                ->or_like('sp.FirstName', $search)
                ->or_like('su.LastName', $search)
                ->or_like('su.FirstName', $search)
                ->group_end();
        }

        return $query->order_by('LastName', 'ASC')
            ->order_by('FirstName', 'ASC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function getStudentByNumber($sy, $semester, $studentNumber)
    {
        $studentNumber = trim((string) $studentNumber);
        if ($studentNumber === '') {
            return null;
        }

        return $this->baseRecipientQuery($sy, $semester)
            ->select($this->studentSelectSql(), false)
            ->where('ss.StudentNumber', $studentNumber)
            ->limit(1)
            ->get()
            ->row();
    }

    public function getRecipients($sy, $semester, array $filters)
    {
        $targetType = trim((string) ($filters['target_type'] ?? 'all'));
        $yearLevel = trim((string) ($filters['year_level'] ?? ''));
        $section = trim((string) ($filters['section'] ?? ''));
        $studentNumber = trim((string) ($filters['student_number'] ?? ''));

        $query = $this->baseRecipientQuery($sy, $semester)
            ->select($this->studentSelectSql() . ",
                COALESCE(NULLIF(TRIM(sp.email), ''), NULLIF(TRIM(su.email), '')) AS email", false);

        if ($targetType === 'year') {
            $query->where('ss.YearLevel', $yearLevel);
        } elseif ($targetType === 'section') {
            $query->where('ss.YearLevel', $yearLevel)
                ->where('ss.Section', $section);
        } elseif ($targetType === 'individual') {
            $query->where('ss.StudentNumber', $studentNumber);
        }

        return $query->order_by('LastName', 'ASC')
            ->order_by('FirstName', 'ASC')
            ->get()
            ->result();
    }

    public function getAnnouncementHistory($limit = 50)
    {
        return $this->db->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get('mass_announcement_posts')
            ->result();
    }

    public function saveAnnouncementPost(array $data)
    {
        $saved = $this->db->insert('mass_announcement_posts', $data);
        if (!$saved) {
            return false;
        }

        return (int) $this->db->insert_id();
    }

    protected function baseRecipientQuery($sy, $semester)
    {
        $query = $this->db->from('semesterstude ss')
            ->join('studeprofile sp', 'sp.StudentNumber = ss.StudentNumber', 'left')
            ->join('studentsignup su', 'su.StudentNumber = ss.StudentNumber', 'left')
            ->where('ss.SY', $sy)
            ->where('ss.Semester', $semester)
            ->group_start()
            ->where('ss.Status', 'Enrolled')
            ->or_where('ss.StudeStatus', 'Enrolled')
            ->group_end();

        return $query;
    }

    protected function applyOptionalTargetFilters(CI_DB_query_builder $query, $yearLevel = '', $section = '')
    {
        $yearLevel = trim((string) $yearLevel);
        $section = trim((string) $section);

        if ($yearLevel !== '') {
            $query->where('ss.YearLevel', $yearLevel);
        }

        if ($section !== '') {
            $query->where('ss.Section', $section);
        }
    }

    protected function studentSelectSql()
    {
        return "ss.StudentNumber,
            ss.YearLevel,
            ss.Section,
            COALESCE(NULLIF(TRIM(sp.FirstName), ''), NULLIF(TRIM(su.FirstName), '')) AS FirstName,
            COALESCE(NULLIF(TRIM(sp.MiddleName), ''), NULLIF(TRIM(su.MiddleName), '')) AS MiddleName,
            COALESCE(NULLIF(TRIM(sp.LastName), ''), NULLIF(TRIM(su.LastName), '')) AS LastName";
    }

    protected function compareYearLevels($left, $right)
    {
        preg_match('/\d+/', (string) $left, $leftMatches);
        preg_match('/\d+/', (string) $right, $rightMatches);

        $leftNumber = isset($leftMatches[0]) ? (int) $leftMatches[0] : PHP_INT_MAX;
        $rightNumber = isset($rightMatches[0]) ? (int) $rightMatches[0] : PHP_INT_MAX;

        if ($leftNumber !== $rightNumber) {
            return $leftNumber <=> $rightNumber;
        }

        return strcasecmp((string) $left, (string) $right);
    }
}
