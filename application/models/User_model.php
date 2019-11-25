<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'role_id',
                'label' => 'role_id',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'is_active',
                'label' => 'is_active',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }

    public function get_all_users()
    {
        $this->select('user.*, role.role');
        $this->join('role');
        return $this->get_all_array();
    }
}

/* End of file User_model.php */