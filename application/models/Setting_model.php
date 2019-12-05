<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'year',
                'label' => 'year',
                'rules' => 'trim',
            ],
            [
                'field' => 'month',
                'label' => 'month',
                'rules' => 'trim',
            ],
            [
                'field' => 'city',
                'label' => 'city',
                'rules' => 'trim',
            ],
            [
                'field' => 'regular_time',
                'label' => 'regular time',
                'rules' => 'trim',
            ],
            [
                'field' => 'semifinal_time',
                'label' => 'semifinal time',
                'rules' => 'trim',
            ],
            [
                'field' => 'final_time',
                'label' => 'final time',
                'rules' => 'trim',
            ],
        ];

        return $validationRules;
    }

    public function update_setting($data)
    {
        return $this->db->update('setting', $data);
    }
}

/* End of file Setting_model.php */