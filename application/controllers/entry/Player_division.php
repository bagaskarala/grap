<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Player_division extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->year    = $this->session->userdata('setting_year');
        $this->city_id = $this->session->userdata('setting_city_id');
    }

    public function index()
    {
        $data['title'] = 'Player Division';
        $data['page']  = 'entry/player_division';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $player_divisions = $this->player_division->get_all_player_division();

        if (count($player_divisions) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($player_divisions) {
            return $this->send_json_output($player_divisions, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'division_id' => $request->division_id,
            'player_id'   => $request->player_id,
            'pool_number' => $request->pool_number,
            'year'        => $this->year,
            'city_id'     => $this->city_id,
        ];

        // validasi
        if ($this->player_division->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            if ($this->player_division->check_player($data)) {
                $this->player_division->insert($data);
                $result = [
                    'status' => true,
                    'data'   => 'Success Insert Player to Division',
                ];
            } else {
                $result = [
                    'status'  => false,
                    'message' => 'Selected player has been added to division',
                ];
            }
        }

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
        }
    }

    public function update($player_division_id)
    {
        $request = parse_post_data();

        $data = [
            'division_id' => $request->division_id,
            'player_id'   => $request->player_id,
            'pool_number' => $request->pool_number,
        ];

        // validasi
        if ($this->player_division->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->player_division->update($data, ['id' => $player_division_id]);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Update Data", false, 400);
        }
    }

    public function delete()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->player_division->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }

    public function filter_division($division_id)
    {
        // menyimpan pilihan divisi di session
        $this->session->set_userdata(['division_id' => $division_id]);

        if ($division_id === 'null') {
            $this->player_division->order_by('pool_number');
            $result = $this->player_division->get_all_player_division();
        } else {
            $this->player_division->order_by('pool_number');
            $result = $this->player_division->filter_division($division_id, $this->year, $this->city_id);
        }

        if (count($result) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed filter data", false, 400);
        }
    }

    public function generate_pool()
    {
        $request = parse_post_data();

        $result = $this->player_division->generate_pool($request->division_id);

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
        }
    }

    public function reset_pool()
    {
        $request = parse_post_data();

        // ketika reset pool, maka reset schedule juga
        $this->load->model('Log_Match_model', 'log_match');
        if (count($this->log_match->filter_division($request->division_id)) != 0) {
            $reset_schedule_result = $this->log_match->reset_schedule($request->division_id);
        } else {
            $reset_schedule_result = true;
        }

        if ($reset_schedule_result) {
            $this->player_division->reset_classement($request->division_id);
            $result = $this->player_division->reset_pool($request->division_id);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Reset Pool", false, 400);
        }
    }

    public function calculate_classement($division_id)
    {
        $result = $this->player_division->calculate_classement($division_id);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed calculate classement", false, 400);
        }
    }

    public function find_all_year()
    {
        $result = $this->player_division->find_all_year();

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed find max year", false, 400);
        }
    }
};