<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referee_model extends MY_Model
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
                'field' => 'description',
                'label' => 'description',
                'rules' => 'trim',
            ],
        ];

        return $validationRules;
    }
}

/* End of file Referee_model.php */
