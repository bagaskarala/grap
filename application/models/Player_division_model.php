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
    }

    public function get_all_player_division()
    {
        $this->query_player_division();
        return $this->get_all_array();
    }

    public function filter_division($division_id)
    {
        // hitung win-lose-draw
        $this->calculate_classement($division_id);

        $this->query_player_division();
        $this->db->where('division_id', $division_id);
        $this->order_by('pool_number');
        $this->order_by('win', 'desc');
        return $this->get_all_array();
    }

    public function calculate_classement($division_id)
    {
        $this->reset_clasement($division_id);

        // get log match
        $this->where('division_id', $division_id);
        $this->where('match_system', 'roundrobin');
        $division_match = $this->get_all_array('log_match');
        if (count($division_match) == 0) {
            return [
                'status'  => false,
                'message' => 'No roundrobin match in this division',
            ];
        }

        // cari pemenang
        $this->select('winner, pd1_id, pd2_id');
        $this->where('division_id', $division_id);
        $this->where('winner !=', null);
        $winner_arr = $this->get_all_array('log_match');

        // hitung win lose draw
        $count_win  = [];
        $count_lose = [];
        $count_draw = [];
        foreach ($winner_arr as $item) {
            if ($item['winner'] == $item['pd1_id']) {
                // jika p1 winner, increment p1 di array win, increment p2 array lose
                $count_win[$item['winner']]  = !array_key_exists($item['winner'], $count_win) ? 1 : $count_win[$item['winner']] + 1;
                $count_lose[$item['pd2_id']] = !array_key_exists($item['pd2_id'], $count_lose) ? 1 : $count_lose[$item['pd2_id']] + 1;
            } elseif ($item['winner'] == $item['pd2_id']) {
                // jika p2 winner, increment p2 di array win, increment p1 array lose
                $count_win[$item['winner']]  = !array_key_exists($item['winner'], $count_win) ? 1 : $count_win[$item['winner']] + 1;
                $count_lose[$item['pd1_id']] = !array_key_exists($item['pd1_id'], $count_lose) ? 1 : $count_lose[$item['pd1_id']] + 1;
            } else {
                // jka draw, increment p1 dan p2 di array draw
                $count_draw[$item['pd1_id']] = !array_key_exists($item['pd1_id'], $count_draw) ? 1 : $count_draw[$item['pd1_id']] + 1;
                $count_draw[$item['pd2_id']] = !array_key_exists($item['pd2_id'], $count_draw) ? 1 : $count_draw[$item['pd2_id']] + 1;
            }
        }

        // insert win ke db
        foreach ($count_win as $pd_id => $win) {
            $this->update(['win' => $win], ['id' => $pd_id]);
        }

        // insert lose ke db
        foreach ($count_lose as $pd_id => $lose) {
            $this->update(['lose' => $lose], ['id' => $pd_id]);
        }

        // insert draw ke db
        foreach ($count_draw as $pd_id => $draw) {
            $this->update(['draw' => $draw], ['id' => $pd_id]);
        }

        return [
            'status' => true,
            'data'   => ['win' => $count_win, 'lose' => $count_lose, 'draw' => $count_draw],
        ];

    }

    public function reset_clasement($division_id)
    {
        // reset klasemen ke 0
        $this->update(['win' => 0, 'lose' => 0, 'draw' => 0], ['division_id' => $division_id]);
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