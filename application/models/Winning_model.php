<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Winning_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'winning',
                'label' => 'winning',
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

/* End of file Winning_model.php */