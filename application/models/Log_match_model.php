<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_match_model extends MY_Model
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
                'field' => 'pd1_id',
                'label' => 'player 1',
                'rules' => 'trim',
            ],
            [
                'field' => 'pd2_id',
                'label' => 'player 2',
                'rules' => 'trim',
            ],
            [
                'field' => 'match_system',
                'label' => 'match system',
                'rules' => 'trim',
            ],
        ];

        return $validationRules;
    }

    public function query_log_match()
    {
        //  get all log match
        // join with player_division two times, using alias for player1 and player2
        // join to player to get player name
        $this->db->select(
            'lm.*,
            d.division,
            pd1.player_id as player1_id,
            p1.name as player1_name,
            c1.club as player1_club,
            d1.division as player1_division,
            pd2.player_id as player2_id,
            p2.name as player2_name,
            c2.club as player2_club,
            d2.division as player2_division'
        );
        $this->db->from("$this->table as lm");
        $this->db->join('player_division as pd1', 'pd1.id = lm.pd1_id', 'left');
        $this->db->join('player as p1', 'p1.id = pd1.player_id', 'left');
        $this->db->join('club as c1', 'c1.id = p1.club_id', 'left');
        $this->db->join('division as d1', 'd1.id = pd1.division_id', 'left');
        $this->db->join('player_division as pd2', 'pd2.id = lm.pd2_id', 'left');
        $this->db->join('player as p2', 'p2.id = pd2.player_id', 'left');
        $this->db->join('club as c2', 'c2.id = p2.club_id', 'left');
        $this->db->join('division as d2', 'd2.id = pd2.division_id', 'left');
        $this->db->join('division as d', 'd.id = lm.division_id', 'left');
    }

    public function get_all_log_match()
    {
        $this->query_log_match();
        return $this->db->get()->result_array();
    }

    public function filter_division($division_id)
    {
        $this->query_log_match();
        $this->db->where('lm.division_id', $division_id);
        return $this->db->get()->result_array();
    }

    public function generate_schedule($division_id, $match_system)
    {
        if (!$division_id or !$match_system) {
            return [
                'status'  => false,
                'message' => 'Error! division_id or match_system undefined',
            ];
        }

        // check if log match has been created
        // avoid create multiple logmatch per division
        $this->where('division_id', $division_id);
        $log_match_count = $this->count();
        if ($log_match_count > 0) {
            return [
                'status'  => false,
                'message' => 'This division log match has been generated',
            ];
        }

        $this->where('division_id', $division_id);
        $player_count = $this->count('player_division');
        if ($player_count == 0) {
            return [
                'status'  => false,
                'message' => 'This division has no player',
            ];
        } else if ($player_count < 4) {
            return [
                'status'  => false,
                'message' => 'Minimal 4 players',
            ];
        }

        $max_match_index = pow(2, ceil(log($player_count, 2)));
        $arr_log_match   = [];

        $divider = $max_match_index / 2;
        for ($idx = 1; $idx <= $max_match_index; $idx++) {
            $next_num = 1;
            for ($num = 1; $num <= $divider; $num++) {
                $next_idx = $idx + 1;
                array_push($arr_log_match, ['idx' => $idx, 'num' => $num, 'next' => "$next_idx.$next_num"]);
                $this->insert([
                    'division_id'  => $division_id,
                    'match_index'  => $idx,
                    'match_system' => $match_system,
                    'match_number' => $num,
                    'next_match'   => "$next_idx.$next_num",
                ]);
                // increment next_num setiap dua loop
                if ($num % 2 == 0) {
                    $next_num++;
                }
            }
            // setiap next index, jumlah pemain separo index sebelumnya
            $divider /= 2;
        }

        return [
            'status' => true,
            'data'   => $arr_log_match,
        ];
    }

    public function reset_schedule($division_id)
    {
        $this->where('division_id', $division_id);
        return $this->delete(['division_id' => $division_id]);
    }
}

/* End of file Log_match_model.php */