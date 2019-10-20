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

    public function get_all_player_division()
    {
        //  get all player division
        // join with division and player
        $this->db->select('player.name, division.division, player_division.id, player_id, division_id, pool_number');
        $this->db->join('division', 'division.id = player_division.division_id', 'left');
        $this->db->join('player', 'player.id = player_division.player_id', 'left');
        $this->db->order_by('division', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    public function filter_division($division_id)
    {
        $this->db->select('player.name, division.division, player_division.id, player_id, division_id, pool_number');
        $this->db->join('division', 'division.id = player_division.division_id', 'left');
        $this->db->join('player', 'player.id = player_division.player_id', 'left');
        $this->db->where('division_id', $division_id);
        return $this->db->get($this->table)->result_array();
    }
}

/* End of file Player_division_model.php */