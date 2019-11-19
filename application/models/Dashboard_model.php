<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends MY_Model
{
    public function get_all_users()
    {
        $this->select('user.*, role.role');
        $this->db->join('role', 'role.id = user.role_id', 'left');
        return $this->dashboard->get_all_array('user');
    }
}

/* End of file Dashboard_model.php */