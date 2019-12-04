<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Achievement extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Achievement';
        $data['page']  = 'master/achievement';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $achievements = $this->achievement->get_all_array();

        if (count($achievements) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($achievements) {
            return $this->send_json_output($achievements, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function filter($player_id)
    {
        $this->achievement->select('achievement.*,city.city');
        $this->achievement->where('player_id', $player_id);
        $this->achievement->join('city');
        $this->achievement->order_by('category');
        $this->achievement->order_by('achievement_year');
        $achievements = $this->achievement->get_all_array();

        if (count($achievements) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($achievements) {
            return $this->send_json_output($achievements, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'player_id'        => $request->player_id,
            'tournament_name'  => $request->tournament_name,
            'winner_position'  => $request->winner_position,
            'city_id'          => $request->city_id,
            'achievement_year' => $request->achievement_year,
            'category'         => $request->category,
            'division_id'      => $request->division_id,
        ];

        // ketika save achievement pada pemenang, ambil setting
        $setting = $this->achievement->get_single_array('setting');

        if ($data['achievement_year'] == null) {
            $data['achievement_year'] = $setting['year'];
        }

        if ($data['city_id'] == null) {
            $data['city_id'] = $setting['city_id'];
        }

        if ($data['tournament_name'] == null) {
            $data['tournament_name'] = 'Super Grappler';
        }

        // validasi
        if ($this->achievement->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->achievement->insert_achievement($data);
        }

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
        }
    }

    public function update($achievement_id)
    {
        $request = parse_post_data();

        $data = [
            'player_id'        => $request->player_id,
            'tournament_name'  => $request->tournament_name,
            'winner_position'  => $request->winner_position,
            'city_id'          => $request->city_id,
            'achievement_year' => $request->achievement_year,
            'category'         => $request->category,
            'division_id'      => $request->division_id,
        ];

        // validasi
        if ($this->achievement->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->achievement->update($data, ['id' => $achievement_id]);
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

        $result = $this->achievement->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};