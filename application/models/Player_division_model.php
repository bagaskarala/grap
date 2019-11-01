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
        $this->db->select("$this->table.*, player.name, player.club_id, division.division, club.club");
        $this->join('division');
        $this->join('player');
        $this->join_table('club', 'player');
        $this->order_by('division');
        $this->order_by('club');
        $this->order_by('name');
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

    public function generate_pool($division_id)
    {
        // MENIRU FUNGSI DI EXCEL, TAPI STUCK
        // $this->query_player_division();
        // $this->order_by('pool_number');
        // $data = $this->db->get($this->table);

        // $count       = $data->num_rows();
        // $mod_count   = $count % 4;
        // $div_count   = floor($count / 4);
        // $pool4_count = 0;
        // $pool3_count = 0;

        // // count 3,4,5 tidak bisa dihitung dengan rumus ini

        // if ($mod_count == 1) {
        //     $pool4_count = $div_count - 2;
        //     $pool3_count = 3;
        // } else {
        //     if ($mod_count == 2) {
        //         $pool4_count = $div_count - 1;
        //         $pool3_count = 2;
        //     } else {
        //         $pool4_count = $div_count;
        //         if ($mod_count == 3) {
        //             $pool3_count = 1;
        //         } else {
        //             $pool3_count = 0;
        //         }
        //     }
        // }
        // $arr_data = $data->result_array();

        // return ['m' => $mod_count, 'd' => $div_count, '4' => $pool4_count, '3' => $pool3_count, 'arr' => $arr_data];

        $this->query_player_division();
        $this->where('division_id', $division_id);
        $this->order_by('club_id');
        $players = $this->get_all_array();

        if (count($players) === 0) {
            return [
                'status'  => false,
                'message' => 'No player in this division',
            ];
        }

        $counter = 1;
        foreach ($players as $player) {
            // odd use pool A, even use pool B
            if ($counter % 2 == 0) {
                $pool_number = 'B';
            } else {
                $pool_number = 'A';
            }

            // update pool_number
            $data  = ['pool_number' => $pool_number];
            $where = ['id' => $player['id']];
            $this->update($data, $where);

            $counter++;
        }
        return [
            'status' => true,
            'data'   => $players,
        ];
    }

    public function reset_pool($division_id)
    {
        $data  = ['pool_number' => null];
        $where = ['division_id' => $division_id];
        return $this->update($data, $where);
    }
}

/* End of file Player_division_model.php */