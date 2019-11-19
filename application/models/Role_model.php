<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'role',
                'label' => 'role',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }
}

/* End of file Role_model.php */