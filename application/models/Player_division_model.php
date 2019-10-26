<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Player_division_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'division_id',
                'label' => 'division',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'player_id',
                'label' => 'player',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'pool_number',
                'label' => 'pool number',
                'rules' => 'trim',
            ],
        ];

        return $validationRules;
    }

    public function query_player_division()
    {
        //  get all player division
        // join with division and player
        $this->db->select("$this->table.*, player.name, division.division");
        $this->join('division');
        $this->join('player');
        $this->order_by('division');
    }

    public function get_all_player_division()
    {
        $this->query_player_division();
        return $this->get_all_array();
    }

    public function filter_division($division_id)
    {
        $this->query_player_division();
        $this->db->where('division_id', $division_id);
        return $this->get_all_array();
    }
}

/* End of file Player_division_model.php */