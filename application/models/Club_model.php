<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Club_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'club',
                'label' => 'club',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'alias',
                'label' => 'alias',
                'rules' => 'trim',
            ],
            [
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim',
            ],
        ];

        return $validationRules;
    }

    public function search($keyword)
    {
        $this->like('club', $keyword);
        $this->or_like('alias', $keyword);
        return $this->get_all_array();
    }
}

/* End of file Club_model.php */