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
        // get all player division
        // join division dan player
        $this->db->select("$this->table.*, player.name, player.weight, player.club_id, division.division, club.club");
        $this->join('division');
        $this->join('player');
        $this->join_table('club', 'player');
    }

    public function get_all_player_division()
    {
        // get semua player division
        $this->query_player_division();
        return $this->get_all_array();
    }

    public function filter_division($division_id, $year, $city_id)
    {
        // hitung win-lose-draw pada divisi terpilih
        // generate winner
        $this->calculate_classement($division_id, $year, $city_id);

        // tampilkan player per divisi
        $this->query_player_division();
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->order_by('pool_number');
        $this->order_by('win', 'desc');
        $this->order_by('total_time');
        $player_divisions = $this->get_all_array();

        $result = [];
        foreach ($player_divisions as $value) {
            // cari achievement grappling terakhir
            $this->select('achievement.*,city.city');
            $this->join_table('city', 'achievement');
            $this->where('player_id', $value['player_id']);
            $this->where('category', 'grappling');
            $this->order_by('achievement_year', 'desc');
            $this->order_by('id', 'desc');
            $value['last_achievement'] = $this->get_single_array('achievement');
            array_push($result, $value);
        }
        return $result;
    }

    public function check_player($data)
    {
        // isi $data = division_id,player_id,pool_number
        // cek apakah player sudah masuk ke player_division
        $check_player = $this->get_where([
            'player_id'   => $data['player_id'],
            'division_id' => $data['division_id'],
            'year'        => $data['year'],
            'city_id'     => $data['city_id'],
        ]);
        if ($check_player) {
            return false;
        } else {
            return true;
        }
    }

    public function calculate_classement($division_id, $year, $city_id)
    {
        // reset perhitungan classement
        $this->reset_classement($division_id, $year, $city_id);

        // cek log match
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $division_match = $this->get_all_array('log_match');
        if (count($division_match) == 0) {
            return [
                'status'  => false,
                'message' => 'No match in this division',
            ];
        } else {
            // cek match_system pada log match
            if ($division_match[0]['match_system'] == 'roundrobin') {
                return $this->calculate_classement_roundrobin($division_id, $year, $city_id);
            } else {
                return $this->calculate_classement_elimination($division_id, $year, $city_id);
            }
        }
    }

    public function calculate_classement_elimination($division_id, $year, $city_id)
    {
        // cek apakah match suatu divisi sudah selesai semua
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->where('winner', null);
        $count_match_unfinished = $this->count('log_match');
        if ($count_match_unfinished > 0) {
            return [
                'status'  => false,
                'message' => 'Elimination match not finished yet',
            ];
        }

        // cari match index terakhir untuk mengtahui partai final
        $this->db->select('MAX(match_index) as max_match_index');
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $idx = $this->get_single_array('log_match');

        // get pertandingan final
        // untuk ambil juara 1 dan 2
        $final_match = $this->get_where([
            'match_index' => $idx['max_match_index'],
            'division_id' => $division_id,
            'year'        => $year,
            'city_id'     => $city_id,
        ], 'log_match');

        // update juara 1
        $this->update(['division_winner' => 1], ['id' => $final_match['winner']]);

        // update juara 2
        if ($final_match['winner'] == $final_match['pd1_id']) {
            $second_winner = $final_match['pd2_id'];
        } else {
            $second_winner = $final_match['pd1_id'];
        }
        $this->update(['division_winner' => 2], ['id' => $second_winner]);

        // get pertandingan third place
        // untuk ambil juara 3
        $third_match = $this->get_where([
            'match_number' => 0,
            'division_id'  => $division_id,
            'year'         => $year,
            'city_id'      => $city_id,
        ], 'log_match', );
        $this->update(['division_winner' => 3], ['id' => $third_match['winner']]);

        $result = true;
        if ($result) {
            return [
                'status' => true,
                'data'   => 'Division winner generated',
            ];
        } else {
            return [
                'status'  => false,
                'message' => 'Failed set winner in elimination match',
            ];
        }
    }

    public function calculate_classement_roundrobin($division_id, $year, $city_id)
    {
        // cari logmatch yang sudah ada pemenang
        $this->select('winner, pd1_id, pd2_id,time');
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->where('winner !=', null);
        $this->where('match_number', null); // cari yang bukan final
        $winner_arr = $this->get_all_array('log_match');

        // reset total time ketika clear schedule
        if (count($winner_arr) == 0) {
            $this->update(['total_time' => 0], [
                'division_id' => $division_id,
                'year'        => $year,
                'city_id'     => $city_id,
            ]);
        }

        // hitung win lose draw
        $count_win  = [];
        $count_lose = [];
        $count_draw = [];
        foreach ($winner_arr as $item) {
            if ($item['winner'] == $item['pd1_id']) {
                // jika p1 winner, increment p1 di array win, increment p2 array lose
                $count_win[$item['winner']] = [
                    'win'  => !array_key_exists($item['winner'], $count_win) ? 1 : $count_win[$item['winner']]['win'] + 1,
                    'time' => !array_key_exists($item['winner'], $count_win) ? $item['time'] : $count_win[$item['winner']]['time'] + $item['time'],
                ];
                $count_lose[$item['pd2_id']] = !array_key_exists($item['pd2_id'], $count_lose) ? 1 : $count_lose[$item['pd2_id']] + 1;
            } elseif ($item['winner'] == $item['pd2_id']) {
                // jika p2 winner, increment p2 di array win, increment p1 array lose
                $count_win[$item['winner']] = [
                    'win'  => !array_key_exists($item['winner'], $count_win) ? 1 : $count_win[$item['winner']]['win'] + 1,
                    'time' => !array_key_exists($item['winner'], $count_win) ? $item['time'] : $count_win[$item['winner']]['time'] + $item['time'],
                ];
                $count_lose[$item['pd1_id']] = !array_key_exists($item['pd1_id'], $count_lose) ? 1 : $count_lose[$item['pd1_id']] + 1;
            } else {
                // jka draw, increment p1 dan p2 di array draw
                $count_draw[$item['pd1_id']] = !array_key_exists($item['pd1_id'], $count_draw) ? 1 : $count_draw[$item['pd1_id']] + 1;
                $count_draw[$item['pd2_id']] = !array_key_exists($item['pd2_id'], $count_draw) ? 1 : $count_draw[$item['pd2_id']] + 1;
            }
        }

        $fail_flag = 0;

        // insert win dan time ke db
        foreach ($count_win as $pd_id => $item) {
            if ($this->update(['win' => $item['win'], 'total_time' => $item['time']], ['id' => $pd_id]) == false) {
                $fail_flag++;
            }
        }

        // insert lose ke db
        foreach ($count_lose as $pd_id => $lose) {
            if ($this->update(['lose' => $lose], ['id' => $pd_id]) == false) {
                $fail_flag++;
            }
        }

        // insert draw ke db
        foreach ($count_draw as $pd_id => $draw) {
            if ($this->update(['draw' => $draw], ['id' => $pd_id]) == false) {
                $fail_flag++;
            }
        }

        // set pemenang pool
        $this->get_pool_winner($division_id, $year, $city_id);

        // final match roundrobin
        $this->update_division_winner_roundrobin($division_id, $year, $city_id);

        return $count_win;

        // if ($fail_flag == 0) {
        //     return [
        //         'status' => true,
        //         'data'   => 'Classement generated',
        //     ];
        // } else {
        //     return [
        //         'status'  => false,
        //         'message' => 'Failed set classement in roundrobin match',
        //     ];
        // }
    }

    public function get_pool_winner($division_id, $year, $city_id)
    {
        // ambil semua pool unik yang ada
        $this->db->distinct();
        $this->db->select('pool_number');
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->where('pool_number !=', null);
        $pd_pools = $this->get_all_array();

        foreach ($pd_pools as $item) {
            // cek apakah match suatu divisi sudah selesai semua
            $this->where('division_id', $division_id);
            $this->where('year', $year);
            $this->where('city_id', $city_id);
            $this->where('pool_number', $item['pool_number']);
            $this->where('winner', null);
            $count_match_unfinished = $this->count('log_match');
            if ($count_match_unfinished > 0) {
                continue;
            }

            // cari player yang win paling banyak
            $this->where('pool_number', $item['pool_number']);
            $this->where('division_id', $division_id);
            $this->where('year', $year);
            $this->where('city_id', $city_id);
            $this->order_by('win', 'desc');
            $this->order_by('total_time');
            $pd = $this->get_all_array();

            if ($pd[0]['win'] != 0) {
                // set pool_winner
                $this->update(['pool_winner' => 1], ['id' => $pd[0]['id']]);
                $this->update(['pool_winner' => 2], ['id' => $pd[1]['id']]);
            }
        }
    }

    public function update_division_winner_roundrobin($division_id, $year, $city_id)
    {
        // ambil semua pool unik yang ada
        $this->db->distinct();
        $this->select('pool_number');
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->where('pool_number !=', null);
        $pools = $this->get_all_array();

        if (count($pools) == 1) {
            // ambil playerdivision sesuai urutan classsement
            $this->query_player_division();
            $this->where('division_id', $division_id);
            $this->where('year', $year);
            $this->where('city_id', $city_id);
            $this->order_by('pool_number');
            $this->order_by('win', 'desc');
            $this->order_by('total_time');
            $player_divisions = $this->get_all_array();

            if ($player_divisions[0]['win'] != 0) {
                // jadikan 3 teratas sebagai juara
                for ($i = 0; $i < 3; $i++) {
                    if ($player_divisions[$i]) {
                        $this->update(['division_winner' => $i + 1], ['id' => $player_divisions[$i]['id']]);
                    }
                }
            }
        } else if (count($pools) == 2) {
            // ambil final match
            $this->where('division_id', $division_id);
            $this->where('year', $year);
            $this->where('city_id', $city_id);
            $this->where('match_index', 1);
            $this->where('match_number', 1);
            $final_match = $this->get_single_array('log_match');

            // ambil third place
            $this->where('division_id', $division_id);
            $this->where('year', $year);
            $this->where('city_id', $city_id);
            $this->where('match_index', 1);
            $this->where('match_number', 2);
            $third_place = $this->get_single_array('log_match');

            // jika match belum selesai, return
            if ($final_match['match_status'] != 2 || $third_place['match_status'] != 2) {
                return;
            }

            // update juara 1
            $this->update(['division_winner' => 1], ['id' => $final_match['winner']]);

            // update juara 2
            $second_winner = null;
            if ($final_match['winner'] == $final_match['pd1_id']) {
                $second_winner = $final_match['pd2_id'];
            } else if ($final_match['winner'] == $final_match['pd2_id']) {
                $second_winner = $final_match['pd1_id'];
            }
            $this->update(['division_winner' => 2], ['id' => $second_winner]);

            // update juara 3
            $this->update(['division_winner' => 3], ['id' => $third_place['winner']]);
        }

    }

    public function reset_classement($division_id, $year, $city_id)
    {
        // reset klasemen win-lose-draw ke 0
        // reset pool_winnerdivision_winner pada divisi terpilih
        $this->db->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->db->update('player_division', ['division_winner' => 0, 'win' => 0, 'lose' => 0, 'draw' => 0, 'pool_winner' => 0]);
    }

    public function generate_pool($division_id, $year, $city_id)
    {
        // get player di divisi
        $this->query_player_division();
        $this->where('division_id', $division_id);
        $this->where('year', $year);
        $this->where('city_id', $city_id);
        $this->order_by('club_id');
        $players = $this->get_all_array();

        if (count($players) === 0) {
            return [
                'status'  => false,
                'message' => 'No player in this division',
            ];
        }
        shuffle($players);

        // acak pool_number ke A dan B
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

    public function reset_pool($division_id, $year, $city_id)
    {
        // kosongkan pool_number
        $data  = ['pool_number' => null];
        $where = [
            'division_id' => $division_id,
            'year'        => $year,
            'city_id'     => $city_id,
        ];

        return $this->update($data, $where);
    }

    public function find_all_year()
    {
        $this->db->distinct();
        $this->select('year');
        $this->where('year !=', 0);
        return $this->get_all_array();
    }
}

/* End of file Player_division_model.php */