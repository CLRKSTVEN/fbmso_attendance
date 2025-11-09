<?php
class Common extends CI_Model{

// common function single row
public function one_cond_row($table, $col, $val){
    $this->db->where($col, $val);
    $result = $this->db->get($table)->row();
    return $result;
}

public function two_cond_row($table,$col,$val,$col2,$val2){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $result = $this->db->get($table)->row();
    return $result;
}

public function three_cond_row($table,$col,$val,$col2,$val2,$col3,$val3){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $result = $this->db->get($table)->row();
    return $result;
}

public function four_cond_row($table,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $this->db->where($col4, $val4);
    $result = $this->db->get($table)->row();
    return $result;
}

public function two_cond_not_equal_row($table,$col,$val,$col2,$val2){
    $this->db->where($col, $val);
    $this->db->where($col2.' != ', $val2);
    $result = $this->db->get($table)->row();
    return $result;
}



//groub by loop

public function no_cond_gb($table,$gb,$ob,$obval){
    $this->db->group_by($gb); 
    $this->db->order_by($ob,$obval);
    $query=$this->db->get($table);
    return $query->result();
}

public function no_cond($table){
    $query=$this->db->get($table);
    return $query->result();
}

public function one_cond_gb($table,$col,$val,$gb,$ob,$obval){
    $this->db->where($col,$val);
    $this->db->group_by($gb); 
    $this->db->order_by($ob,$obval);
    $query=$this->db->get($table);
    return $query->result();
}


//loop
public function one_cond($table,$col,$val){
    $this->db->where($col,$val);
    $query=$this->db->get($table);
    return $query->result();
}

public function two_cond($table,$col,$val,$col2,$val2){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $query=$this->db->get($table);
    return $query->result();
}

public function three_cond($table,$col,$val,$col2,$val2,$col3,$val3){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->where($col3,$val3);
    $query=$this->db->get($table);
    return $query->result();
}

public function three_cond_ob($table,$col,$val,$col2,$val2,$col3,$val3,$ob,$obval){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->where($col3,$val3);
    $this->db->order_by($ob,$obval);
    $query=$this->db->get($table);
    return $query->result();
}

public function four_cond($table,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->where($col3,$val3);
    $this->db->where($col4,$val4);
    $query=$this->db->get($table);
    return $query->result();
}

//Loop with group by

public function two_cond_gb($table,$col,$val,$col2,$val2,$gb,$ob,$obval){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->group_by($gb);
    $this->db->order_by($ob,$obval);
    $query=$this->db->get($table);
    return $query->result();
}

public function three_cond_gb($table,$col,$val,$col2,$val2,$col3,$val3,$gb,$ob,$obval){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->where($col3,$val3);
    $this->db->group_by($gb);
    $this->db->order_by($ob,$obval);
    $query=$this->db->get($table);
    return $query->result();
}

//loop with not equal
public function two_cond_ne($table,$col,$val,$col2,$val2,$col3,$val3){
    $this->db->where($col,$val);
    $this->db->where($col2,$val2);
    $this->db->where($col3.'!=',$val3);
    $query=$this->db->get($table);
    return $query->result();
}




//join queries

public function two_join_two_cond_gb($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$gb,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2);  
    $this->db->group_by($gb);
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_one_cond($t1,$t2,$select,$joinby,$col,$val,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);   
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_two_cond($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2);  
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_three_condv2($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$case,$caseorder,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);   
    $this->db->where($col2, $val2); 
    $this->db->where($col3, $val3);  
    $this->db->order_by($case,$caseorder);
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}


public function two_join_four_condv2($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4,$case,$caseorder,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);   
    $this->db->where($col2, $val2); 
    $this->db->where($col3, $val3);  
    $this->db->where($col4, $val4);  
    $this->db->order_by($case,$caseorder);
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_four_condv2_count($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4,$case,$caseorder,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);   
    $this->db->where($col2, $val2); 
    $this->db->where($col3, $val3);  
    $this->db->where($col4, $val4);  
    $this->db->order_by($case,$caseorder);
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query;
}

public function two_join_three_cond($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2); 
    $this->db->where($col3, $val3);
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_four_cond($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$col4,$val4,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2); 
    $this->db->where($col3, $val3);
    $this->db->where($col4, $val4);  
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function two_join_no_cond($t1,$t2,$select,$joinby,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

// common function count

public function two_join_two_cond_count($t1,$t2,$select,$joinby,$col,$val,$col2,$val2){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2);
    $query = $this->db->get();
    return $query;
}

public function two_join_three_cond_count($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $query = $this->db->get();
    return $query;
}

public function two_join_four_cond_count($t1,$t2,$select,$joinby,$col,$val,$col2,$val2,$col3,$val3,$col4, $val4){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val);  
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $this->db->where($col4, $val4);
    $query = $this->db->get();
    return $query;
}

public function one_cond_count_row($table, $col, $val){
    $this->db->where($col, $val);
    $result = $this->db->get($table);
    return $result;
}

public function two_cond_count_row($table, $col,$val,$col2,$val2){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $result = $this->db->get($table);
    return $result;
}

public function three_cond_not_equal_count_row($table, $col,$val,$col2,$val2,$col3,$val3){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3.' != ', $val3);
    $result = $this->db->get($table);
    return $result;
}

public function three_cond_count_row($table, $col,$val,$col2,$val2,$col3,$val3){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $result = $this->db->get($table);
    return $result;
}

public function four_cond_count_row($table, $col,$val,$col2,$val2,$col3,$val3,$col4,$val4){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $this->db->where($col4, $val4);
    $result = $this->db->get($table);
    return $result;
}

public function four_cond_count_row_one_not_equal($table, $col,$val,$col2,$val2,$col3,$val3,$col4,$val4){
    $this->db->where($col, $val);
    $this->db->where($col2, $val2);
    $this->db->where($col3, $val3);
    $this->db->where($col4.' != ', $val4);
    $result = $this->db->get($table);
    return $result;
}



public function getStudeProfiles() {
    $this->db->order_by('LastName', 'ASC');
    $query = $this->db->get('studeprofile');
    return $query->result();
}

public function cons($yl,$section){
        $this->db->select('*');
        $this->db->from('grades g');
        $this->db->join('studeprofile s', 'g.StudentNumber = s.StudentNumber');
        $this->db->where('g.SY',$this->session->sy);
        $this->db->where('g.YearLevel',$yl);
        $this->db->where('g.Section',$section);
        $this->db->group_by('g.StudentNumber');
        $this->db->order_by("CASE WHEN s.Sex = 'Female' THEN 1 ELSE 2 END", 'Desc');
        $query = $this->db->get();
        return $query->result();
}

public function consv2($yl, $section) {
    $this->db->select('*');
    $this->db->from('grades g');
    $this->db->join('studeprofile s', 'g.StudentNumber = s.StudentNumber');
    $this->db->where('g.SY', $this->session->sy);
    $this->db->where('g.YearLevel', $yl);
    $this->db->where('g.Section', $section);
    $this->db->group_by('g.StudentNumber');
    $this->db->order_by('g.Average', 'DESC');  

    $query = $this->db->get();
    return $query->result();
}

public function consv3($yl, $section) {
    $this->db->select('*');
    $this->db->from('grades g');
    $this->db->join('studeprofile s', 'g.StudentNumber = s.StudentNumber');
    $this->db->where('g.SY', $this->session->sy);
    $this->db->where('g.YearLevel', $yl);
    $this->db->where('g.Section', $section);
    $this->db->group_by('g.StudentNumber');
    $this->db->order_by('s.LastName', 'ASC');  

    $query = $this->db->get();
    return $query->result();
}

public function two_join_one_cond_gb($t1,$t2,$select,$joinby,$col,$val,$ob,$obval){
    $this->db->select($select);
    $this->db->from($t1.' as a');
    $this->db->join($t2.' as b', $joinby, 'left');
    $this->db->where($col, $val); 
    $this->db->group_by('a.StudentNumber');  
    $this->db->order_by($ob,$obval);
    $query = $this->db->get(); 
    return $query->result();
}

public function updateGrade($data, $gradeID) {
    // Assuming you have a `grades` table where you store student grades
    $this->db->where('gradeID', $gradeID);
    return $this->db->update('grades', $data);
}

public function getGrades() {
    // Query to get grades data from the database
    $this->db->select('*');
    $this->db->from('grades');
    return $this->db->get()->result();
}

public function delete($table, $col_id, $segment)
    {
        $id = $this->uri->segment($segment);
        $this->db->where($col_id, $id);
        $this->db->delete($table);
        return true;
    }



















}