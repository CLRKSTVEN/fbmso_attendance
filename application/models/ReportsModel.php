<?php defined('BASEPATH') or exit('No direct script access allowed');

class ReportsModel extends CI_Model
{
    /* ================================================================
     * Students / Enrollment (semesterstude)
     * ================================================================ */

    public function students_by_yearlevel($sy, $sem)
    {
        $sql = "SELECT COALESCE(YearLevel,'') AS YearLevel,
                       COUNT(DISTINCT StudentNumber) AS total
                  FROM semesterstude
                 WHERE SY = ? AND Semester = ?
              GROUP BY COALESCE(YearLevel,'')
              ORDER BY YearLevel";
        return $this->db->query($sql, [$sy, $sem])->result();
    }

    public function students_by_course($sy, $sem)
    {
        $sql = "SELECT COALESCE(Course,'') AS Course,
                       COUNT(DISTINCT StudentNumber) AS total
                  FROM semesterstude
                 WHERE SY = ? AND Semester = ?
              GROUP BY COALESCE(Course,'')
              ORDER BY Course";
        return $this->db->query($sql, [$sy, $sem])->result();
    }

    public function students_by_section($sy, $sem, $course = null, $yearLevel = null)
    {
        $where  = ["SY = ?", "Semester = ?"];
        $params = [$sy, $sem];

        if (!empty($course)) {
            $where[] = "Course = ?";
            $params[] = $course;
        }
        if (!empty($yearLevel)) {
            $where[] = "YearLevel = ?";
            $params[] = $yearLevel;
        }

        $sql = "SELECT COALESCE(Course,'') AS Course,
                       COALESCE(YearLevel,'') AS YearLevel,
                       COALESCE(Section,'') AS Section,
                       COUNT(DISTINCT StudentNumber) AS total
                  FROM semesterstude
                 WHERE " . implode(' AND ', $where) . "
              GROUP BY COALESCE(Course,''), COALESCE(YearLevel,''), COALESCE(Section,'')
              ORDER BY Course, YearLevel, Section";
        return $this->db->query($sql, $params)->result();
    }

    public function courses_list()
    {
        $sql = "SELECT DISTINCT Course FROM semesterstude
                 WHERE Course IS NOT NULL AND Course <> '' ORDER BY Course";
        return $this->db->query($sql)->result();
    }

    public function yearlevels_list()
    {
        $sql = "SELECT DISTINCT YearLevel FROM semesterstude
                 WHERE YearLevel IS NOT NULL AND YearLevel <> '' ORDER BY YearLevel";
        return $this->db->query($sql)->result();
    }

    /* ================================================================
     * Sections per course (course_table + course_sections)
     * ================================================================ */

    public function sections_count_by_course()
    {
        $sql = "SELECT 
                    CONCAT(ct.CourseCode, ' — ', ct.CourseDescription) AS Course,
                    COUNT(DISTINCT cs.section) AS sections
                FROM course_table ct
           LEFT JOIN course_sections cs
                  ON cs.courseid  = ct.courseid
                 AND (cs.is_active = 1 OR cs.is_active IS NULL)
            GROUP BY ct.courseid, ct.CourseCode, ct.CourseDescription
            ORDER BY ct.CourseCode";
        return $this->db->query($sql)->result();
    }

    /* ================================================================
     * Events & Attendance (activities + activity_attendance + studentsignup)
     *
     * IMPORTANT FIX:
     * If $sy/$sem are provided, include rows where activities.sy/semester
     * are NULL or empty so existing events still appear.
     * ================================================================ */

    /** Build WHERE for events: include NULL/'' when filtering by SY/Sem. */
    private function _event_where_include_null(&$params, $alias = 'a', $sy = null, $sem = null)
    {
        $w = [];
        if (isset($sy) && $sy !== '') {
            $w[] = "($alias.sy = ? OR $alias.sy IS NULL OR $alias.sy = '')";
            $params[] = $sy;
        }
        if (isset($sem) && $sem !== '') {
            $w[] = "($alias.semester = ? OR $alias.semester IS NULL OR $alias.semester = '')";
            $params[] = $sem;
        }
        return $w;
    }
    public function events_summary($sy = null, $sem = null)
    {
        // include NULL/'' when filtering by SY/Sem so events still show
        $params = [];
        $w = [];
        if (isset($sy) && $sy !== '') {
            $w[] = "(a.sy = ? OR a.sy IS NULL OR a.sy = '')";
            $params[] = $sy;
        }
        if (isset($sem) && $sem !== '') {
            $w[] = "(a.semester = ? OR a.semester IS NULL OR a.semester = '')";
            $params[] = $sem;
        }
        $where_sql = $w ? ('WHERE ' . implode(' AND ', $w)) : '';

        // Pull common possible keys from meta; keep originals as fallback
        $sql = "SELECT 
                a.activity_id,
                a.title,
                a.activity_date,
                a.program,
                a.start_at,            /* fallback */
                a.end_at,              /* fallback */
                /* meta-based values (JSON); all returned as text */
                JSON_UNQUOTE(JSON_EXTRACT(a.meta, '$.start_time')) AS meta_start_time,
                JSON_UNQUOTE(JSON_EXTRACT(a.meta, '$.end_time'))   AS meta_end_time,
                JSON_UNQUOTE(JSON_EXTRACT(a.meta, '$.start'))      AS meta_start,
                JSON_UNQUOTE(JSON_EXTRACT(a.meta, '$.end'))        AS meta_end,
                COUNT(DISTINCT CONCAT_WS('|', aa.activity_id, aa.student_number, COALESCE(aa.session,''))) AS scans
            FROM activities a
            LEFT JOIN activity_attendance aa
              ON aa.activity_id = a.activity_id
            $where_sql
            GROUP BY a.activity_id, a.title, a.activity_date, a.program, a.start_at, a.end_at,
                     meta_start_time, meta_end_time, meta_start, meta_end
            ORDER BY a.activity_date DESC, a.start_at DESC, a.activity_id DESC
            LIMIT 20";
        return $this->db->query($sql, $params)->result();
    }

    public function events_total($sy = null, $sem = null)
    {
        $params = [];
        $where  = $this->_event_where_include_null($params, 'a', $sy, $sem);
        $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "SELECT COUNT(*) AS total
                  FROM activities a
                $where_sql";
        return (int)$this->db->query($sql, $params)->row()->total;
    }

    public function event_scans_total($sy = null, $sem = null)
    {
        $params = [];
        $where  = $this->_event_where_include_null($params, 'a', $sy, $sem);
        $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "SELECT COUNT(DISTINCT CONCAT_WS('|', aa.activity_id, aa.student_number, COALESCE(aa.session,''))) AS scans
                  FROM activities a
             LEFT JOIN activity_attendance aa
                    ON aa.activity_id = a.activity_id
                $where_sql";
        return (int)$this->db->query($sql, $params)->row()->scans;
    }

    /** Recent attendance with student names. */
    public function attendance_recent($sy = null, $sem = null, $limit = 100)
    {
        $params = [];
        $where  = $this->_event_where_include_null($params, 'a', $sy, $sem);
        $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $limit = (int)$limit;
        if ($limit <= 0) $limit = 100;

        $sql = "SELECT 
                    aa.id,
                    aa.activity_id,
                    a.title AS activity_title,
                    aa.session,
                    aa.student_number,
                    s.FirstName,
                    s.MiddleName,
                    s.LastName,
                    s.yearLevel,
                    s.section,
                    COALESCE(NULLIF(s.Course3,''), NULLIF(s.Course1,''), NULLIF(s.Course2,'')) AS CourseName,
                    aa.checked_in_at,
                    aa.checked_out_at,
                    aa.scan_date,
                    aa.source,
                    aa.remarks
                FROM activities a
           INNER JOIN activity_attendance aa
                    ON aa.activity_id = a.activity_id
            LEFT JOIN studentsignup s
                    ON s.StudentNumber = aa.student_number
                $where_sql
            ORDER BY aa.checked_in_at DESC, aa.id DESC
               LIMIT $limit";
        return $this->db->query($sql, $params)->result();
    }
}
