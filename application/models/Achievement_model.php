<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Achievement_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'player_id',
                'label' => 'player id',
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
                'field' => 'city_id',
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
                'field' => 'division_id',
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
        $achievements = $this->get_all_array();

        foreach ($achievements as $ach) {
            if ($ach['city_id'] == $data['city_id']
                and $ach['division_id'] == $data['division_id']
                and $ach['winner_position'] == $data['winner_position']
                and $ach['achievement_year'] == $data['achievement_year']) {
                return [
                    'status'  => false,
                    'message' => 'This achievement has been saved',
                ];
            }
        }

        $count_achievement = $this->count($achievements);
        if ($count_achievement == 3) {
            // cek achievement terlama
            $this->select('MIN(achievement_year) as oldest_achievement, id');
            $this->where('player_id', $data['player_id']);
            $this->where('category', $data['category']);
            $item = $this->get_single_array('achievement');

            // delete achievement terlama, insert achievement baru
            $this->delete(['id' => $item['id']]);
            $result = $this->insert($data);

            if ($result) {
                return [
                    'status' => true,
                    'data'   => 'Success save achievement',
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => 'Failed save achievement',
                ];
            }
        }

        if ($this->insert($data)) {
            return [
                'status' => true,
                'data'   => 'Success insert achievement',
            ];
        } else {
            return [
                'status'  => false,
                'message' => 'Failed insert achievement',
            ];
        }
    }
}

/* End of file Achievement_model.php */