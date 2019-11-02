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
            w.winning,
            pd1.player_id as player1_id,
            p1.name as player1_name,
            p1.weight as player1_weight,
            c1.club as player1_club,
            ct1.country as player1_country,
            pd2.player_id as player2_id,
            p2.name as player2_name,
            p2.weight as player2_weight,
            c2.club as player2_club,
            ct2.country as player2_country'
        );
        $this->db->from("$this->table as lm");
        $this->db->join('player_division as pd1', 'pd1.id = lm.pd1_id', 'left');
        $this->db->join('player as p1', 'p1.id = pd1.player_id', 'left');
        $this->db->join('club as c1', 'c1.id = p1.club_id', 'left');
        $this->db->join('country as ct1', 'ct1.id = p1.country_id', 'left');
        $this->db->join('player_division as pd2', 'pd2.id = lm.pd2_id', 'left');
        $this->db->join('player as p2', 'p2.id = pd2.player_id', 'left');
        $this->db->join('club as c2', 'c2.id = p2.club_id', 'left');
        $this->db->join('country as ct2', 'ct2.id = p2.country_id', 'left');
        $this->db->join('division as d', 'd.id = lm.division_id', 'left');
        $this->db->join('winning as w', 'w.id = lm.winning_id', 'left');
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

    public function generate_player($division_id)
    {
        // handling, generate player hanya sekali
        $this->where('division_id', $division_id);
        $this->where('pd1_id !=', null);
        $this->where('pd2_id !=', null);
        $count_generated_player = $this->count();
        if ($count_generated_player > 0) {
            return [
                'status'  => false,
                'message' => 'Player has been generated',
            ];
            exit();
        }

        // get player division, urutkan club_id untuk minimalisir club sama bertemu
        $this->select('player_division.*, player.club_id');
        $this->join_table('player', 'player_division');
        $this->where('division_id', $division_id);
        $this->order_by('club_id');
        $player_divisions = $this->get_all_array('player_division');
        if (count($player_divisions) == 0) {
            return [
                'status'  => false,
                'message' => 'This division has no player',
            ];
        }

        // hitung max log_match index 1
        $this->where('division_id', $division_id);
        $this->where('match_index', 1);
        $log_match_count = $this->count();
        if ($log_match_count == 0) {
            return [
                'status'  => false,
                'message' => 'No schedule in this match',
            ];
        }

        $arr_generate_player = [];

        // loop player_division,
        $index_counter = 1;
        $flag          = true;
        foreach ($player_divisions as $player_division) {
            // flag true menandakan player1 sudah penuh,
            // setelah itu set flag false untuk mengisi player2
            if ($index_counter == $log_match_count + 1) {
                $flag          = false;
                $index_counter = 1;
            }

            if ($flag) {
                // isi player1
                $where = [
                    'match_index'  => 1,
                    'match_number' => $index_counter,
                    'division_id'  => $division_id,
                ];
                $this->update(['pd1_id' => $player_division['id']], $where);
                array_push($arr_generate_player, ['pd1' => $player_division['id'], 'idx' => $index_counter]);
            } else {
                // isi player2
                $where = [
                    'match_index'  => 1,
                    'match_number' => $index_counter,
                    'division_id'  => $division_id,
                ];
                $this->update(['pd2_id' => $player_division['id']], $where);
                array_push($arr_generate_player, ['pd2' => $player_division['id'], 'idx' => $index_counter]);
            }
            $index_counter++;
        }

        return [
            'status' => true,
            'data'   => $arr_generate_player,
        ];
    }

    public function reset_player($division_id)
    {
        $data = [
            'pd1_id'         => null,
            'pd1_redcard'    => 0,
            'pd1_yellowcard' => 0,
            'pd1_greencard'  => 0,
            'pd1_point'      => 0,
            'pd2_id'         => null,
            'pd2_redcard'    => 0,
            'pd2_yellowcard' => 0,
            'pd2_greencard'  => 0,
            'pd2_point'      => 0,
            'winning_id'     => null,
            'winner'         => null,
            'match_status'   => 0,
            'time'           => null,
        ];
        return $this->update($data, ['division_id' => $division_id]);
    }

    public function get_detail_log_match($log_match_id)
    {
        $this->query_log_match();
        $this->db->where('lm.id', $log_match_id);
        return $this->db->get()->row_array();
    }

    public function start_play($division_id)
    {
        $this->where('division_id', $division_id);
        $this->where('match_index', 1);
        $log_matchs_first = $this->get_all_array();

        // loop logmatch berindex 1/ pertandingan pertama
        foreach ($log_matchs_first as $lm) {
            if ($lm['pd1_id'] == null or $lm['pd2_id'] == null) {
                // baca index dan number untuk next-match
                $index  = explode('.', $lm['next_match'])[0];
                $number = explode('.', $lm['next_match'])[1];

                // pilih playerdivision yang ada isinya
                if ($lm['pd1_id']) {
                    $pd_selected = 'pd1_id';
                } else {
                    $pd_selected = 'pd2_id';
                }

                // logmatch tujuan
                $where = [
                    'match_index'  => $index,
                    'match_number' => $number,
                    'division_id'  => $division_id,
                ];
                $destination = $this->get_where($where);

                // update logmatch tujuan
                if ($destination['pd1_id'] == null) {
                    $data = ['pd1_id' => $lm[$pd_selected]];
                    $this->update($data, $where);
                } else {
                    $data = ['pd2_id' => $lm[$pd_selected]];
                    $this->update($data, $where);
                }

                // update logmatch asal
                $data = [
                    'winner'       => 4, // bye
                    'match_status' => 2, // finish
                    'pd1_id'       => null,
                    'pd2_id'       => null,
                ];
                $this->update($data, ['id' => $lm['id']]);
            }
        }
    }

    public function next_play($log_match_id)
    {
        // ambil log match asal
        $log_match = $this->get_where(['id' => $log_match_id]);

        // update winner pada match final ke playerdivision
        $this->db->select('MAX(match_index) as max_match_index');
        $idx = $this->get_single_array();
        if ($idx['max_match_index'] == $log_match['match_index']) {
            $this->update(['division_winner' => 1], ['id' => $log_match['division_id']], 'player_division');
            exit();
        }

        // baca index dan number untuk next-match
        $index  = explode('.', $log_match['next_match'])[0];
        $number = explode('.', $log_match['next_match'])[1];

        // pilih playerdivision yang menang
        $pd_selected = null;
        if ($log_match['winner'] == 1) {
            $pd_selected = 'pd1_id';
        } elseif ($log_match['winner'] == 2) {
            $pd_selected = 'pd2_id';
        }

        // logmatch tujuan
        $where = [
            'match_index'  => $index,
            'match_number' => $number,
            'division_id'  => $log_match['division_id'],
        ];
        $destination = $this->get_where($where);

        // update logmatch tujuan
        $arr_source = [$log_match['pd1_id'], $log_match['pd2_id']];
        if ($destination['pd1_id'] != null) {
            // jika pd1 tujuan terisi
            // cek apakah pd1 tujuan mempunyai id yang sama dengan sumber
            if (in_array($destination['pd1_id'], $arr_source)) {
                // jika sama, maka update pd1
                $data = ['pd1_id' => $log_match[$pd_selected]];
                $this->update($data, $where);
            } else {
                // jika beda, maka update pd2 / mengisi kolom yang kosong
                $data = ['pd2_id' => $log_match[$pd_selected]];
                $this->update($data, $where);
            }
        } elseif ($destination['pd1_id'] == null) {
            // jika pd1 tujuan kosong
            $data = ['pd1_id' => $log_match[$pd_selected]];
            $this->update($data, $where);
        }
    }

}

/* End of file Log_match_model.php */