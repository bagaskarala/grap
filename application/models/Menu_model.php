<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'menu',
                'label' => 'menu',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }
}

/* End of file Menu_model.php */