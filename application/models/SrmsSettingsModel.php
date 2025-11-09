<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SrmsSettingsModel extends CI_Model
{
    private $table = 'o_srms_settings';
    private $pk    = 'settingsID'; 

    public function get_setting()
    {
        return $this->db->limit(1)->get($this->table)->row();
    }

    public function is_online_payments_enabled(): bool
    {
        $row = $this->get_setting();
        return !empty($row) && (int)$row->show_online_payments === 1;
    }

   
    public function update_toggle($show)
    {
        $show = (int) !!$show;

        $row = $this->get_setting();
        if ($row && isset($row->{$this->pk})) {
            return $this->db->update(
                $this->table,
                ['show_online_payments' => $show],
                [$this->pk => $row->{$this->pk}]
            );
        }

        return $this->db->update($this->table, ['show_online_payments' => $show]);
    }
}
