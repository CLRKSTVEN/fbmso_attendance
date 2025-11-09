<?php defined('BASEPATH') or exit('No direct script access allowed');

class FbmsoPersonnelsModel extends CI_Model
{

    private $t = 'fbmso_personnels';

    public function all_active()
    {
        return $this->db->where('is_active', 1)->order_by('sort_order, full_name')->get($this->t)->result();
    }

    public function all()
    {
        return $this->db->order_by('sort_order, full_name')->get($this->t)->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->t, ['id' => $id])->row();
    }

    public function upsert($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id)->update($this->t, $data);
            return $id;
        } else {
            $this->db->insert($this->t, $data);
            return $this->db->insert_id();
        }
    }

    public function delete($id)
    {
        $this->db->delete($this->t, ['id' => $id]);
    }

    public function toggle($id, $active)
    {
        $this->db->where('id', $id)->update($this->t, ['is_active' => $active ? 1 : 0]);
    }
}
