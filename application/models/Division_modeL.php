<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Division_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'division',
                'label' => 'division',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'system',
                'label' => 'system',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'description',
                'label' => 'description',
                'rules' => 'trim',
            ],
            [
                'field' => 'play',
                'label' => 'play',
                'rules' => 'required|trim|exact_length[1]',
            ],

        ];

        return $validationRules;
    }
}

/* End of file Division_model.php */