<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Achievement_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'player_id',
                'label' => 'player_id',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'tournament_name',
                'label' => 'tournament name',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'winner_position',
                'label' => 'winner position',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'achievement_city',
                'label' => 'city',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'achievement_year',
                'label' => 'year',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'category',
                'label' => 'category',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'division',
                'label' => 'division',
                'rules' => 'required|trim',
            ],
        ];

        return $validationRules;
    }

    public function insert_achievement($data)
    {
        $this->where('category', $data['category']);
        $this->where('player_id', $data['player_id']);
        $count_achievement = $this->count();
        if ($count_achievement == 3) {

            //     $this->db->select('MIN(ACHIevement_year) as oldest_achievement');
            // $this->where('division_id', $log_match['division_id']);
            // $idx = $this->get_single_array();

            return [
                'status'  => false,
                'message' => '3 max achievement per category, just edit your oldest achievement',
            ];
        }

        if ($this->insert($data)) {
            return [
                'status' => true,
                'data'   => 'good',
            ];
        }
    }
}

/* End of file Achievement_model.php */