<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Player_model extends MY_Model
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
                'field' => 'country_id',
                'label' => 'country',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'club_id',
                'label' => 'club',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'alias',
                'label' => 'alias',
                'rules' => 'trim',
            ],
            [
                'field' => 'gender',
                'label' => 'gender',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'img',
                'label' => 'img',
                'rules' => 'trim',
            ],
            [
                'field' => 'height',
                'label' => 'height',
                'rules' => 'numeric|trim',
            ],
            [
                'field' => 'weight',
                'label' => 'weight',
                'rules' => 'numeric|trim',
            ],
            [
                'field' => 'achievement',
                'label' => 'achievement',
                'rules' => 'integer|trim',
            ],
        ];

        return $validationRules;
    }

    public function get_all_player()
    {
        $this->db->select("$this->table.*, club.club, country.country");
        $this->join('club');
        $this->join('country');
        $this->order_by('gender');
        $this->order_by('name');
        return $this->get_all_array();
    }

    public function filter($min_weight, $max_weight)
    {
        if (!$min_weight) {
            $min_weight = 0;
        }
        if (!$max_weight) {
            $max_weight = 200;
        }

        // get filtered player
        $this->select("$this->table.*, club.club, country.country");
        $this->join('club');
        $this->join('country');
        $this->where('weight >', $min_weight);
        $this->where('weight <', $max_weight);
        $this->order_by('weight');
        $this->order_by('name');
        return $this->get_all_array();
    }
}

/* End of file Player_model.php */