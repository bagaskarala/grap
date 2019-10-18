<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Country_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'country',
                'label' => 'country',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'img',
                'label' => 'img',
                'rules' => 'trim',
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

/* End of file Country_model.php */