<?php
class CourseSectionModel extends CI_Model {

    // Get all sections
    public function getAllSections() {
        $this->db->select('cs.*, ct.CourseCode, ct.CourseDescription');
        $this->db->from('course_sections cs');
        $this->db->join('course_table ct', 'ct.courseid = cs.courseid', 'left');
        $query = $this->db->get();
        return $query->result();
    }

      public function addSection($data) {
        return $this->db->insert('course_sections', $data);
    }
    // Update section
    public function updateSection($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('course_sections', $data);
    }

    // Get section by ID
    public function getSectionById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('course_sections');
        return $query->row();
    }

    // Delete section
    public function deleteSection($id) {
        $this->db->where('id', $id);
        return $this->db->delete('course_sections');
    }

    public function getYearLevels()
    {
        $this->db->distinct();
        $this->db->select('year_level');
        $this->db->from('course_sections');
        $this->db->order_by('year_level', 'ASC');

        return $this->db->get()->result();
    }

 public function getCourses() {
    $this->db->select('*');
    $this->db->from('course_table');
    $query = $this->db->get();
    return $query->result();  // Return the courses
}


}


