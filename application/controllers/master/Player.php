<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Player extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Player';
        $data['page']  = 'master/player';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $players = $this->player->get_all_array();

        if (count($players) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($players) {
            return $this->send_json_output($players, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'country_id'  => $request->country_id,
            'name'        => $request->name,
            'img'         => $request->img,
            'height'      => $request->height,
            'weight'      => $request->weight,
            'achievement' => $request->achievement,
        ];

        $result = $this->player->insert($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($player_id)
    {
        $request = parse_post_data();

        $data = [
            'country_id'  => $request->country_id,
            'name'        => $request->name,
            'img'         => $request->img,
            'height'      => $request->height,
            'weight'      => $request->weight,
            'achievement' => $request->achievement,
        ];

        // $result = $this->division->insert($data);
        $result = $this->player->update($data, ['id' => $player_id]);

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

        $result = $this->player->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};