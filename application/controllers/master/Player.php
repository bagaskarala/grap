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

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data'    => $players,
            ]));
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

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal insert',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => ['insert_id' => $result],
                ]));
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

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal update',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => $result,
                ]));
        }
    }

    public function delete()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->player->delete($data);

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal delete',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => $result,
                ]));
        }
    }
};