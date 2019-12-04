<?php
defined('BASEPATH') or exit('No direct script access allowed');

class City_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'city',
                'label' => 'city',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }
}

/* End of file City_model.php */