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
                'field' => 'division_id',
                'label' => 'division_id',
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

            // cek achievement terlama
            $this->db->select('MIN(achievement_year) as oldest_achievement, id');
            $this->where('player_id', $data['player_id']);
            $this->where('category', $data['category']);
            $this->order_by('id');
            $item = $this->get_single_array('achievement');

            // update data lama,
            $result = $this->update($data, ['id' => $item['id']]);

            if ($result) {
                return [
                    'status' => true,
                    'data'   => 'Success update old achievement',
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => $item,
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