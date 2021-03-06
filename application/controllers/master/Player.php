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
        $players = $this->player->get_all_player();

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
            'country_id' => $request->country_id,
            'club_id'    => $request->club_id,
            'name'       => $request->name,
            'nickname'   => $request->nickname,
            'gender'     => $request->gender,
            'height'     => $request->height,
            'weight'     => $request->weight,
        ];

        // validasi
        if ($this->player->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->player->insert($data);
        }

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
            'country_id' => $request->country_id,
            'club_id'    => $request->club_id,
            'name'       => $request->name,
            'nickname'   => $request->nickname,
            'gender'     => $request->gender,
            'height'     => $request->height,
            'weight'     => $request->weight,
        ];

        // validasi
        if ($this->player->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->player->update($data, ['id' => $player_id]);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Update Data", false, 400);
        }
    }

    public function upload_photo($id, $photo_type)
    {
        $upload_image = $_FILES[$photo_type]['name'];

        if ($upload_image) {
            $result = $this->player->upload_photo($id, $photo_type);
        } else {
            $result = [
                'status'  => false,
                'message' => 'no photo uploaded',
            ];
        }

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
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

    public function filter()
    {
        $request = parse_post_data();

        $result = $this->player->filter($request->division_id, $request->min_weight, $request->max_weight);

        if (count($result) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function search($keyword = null)
    {
        if ($keyword === null) {
            $result = $this->player->get_all_player();
        } else {
            $result = $this->player->search($keyword);
        }

        if (count($result) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed search data", false, 400);
        }
    }
};