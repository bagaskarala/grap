<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sub_menu_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'menu_id',
                'label' => 'menu',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'url',
                'label' => 'url',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'icon',
                'label' => 'icon',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'is_active',
                'label' => 'is active',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }

    public function get_sub_menu()
    {
        $this->select('sub_menu.*, menu.menu,');
        $this->join('menu');
        $this->order_by('menu.id');
        return $this->get_all_array();
    }
}

/* End of file Menu_model.php */