<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Winning extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Winning';
        $data['page']  = 'master/winning';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $winnings = $this->winning->get_all_array();

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data'    => $winnings,
            ]));
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'winning'     => $request->winning,
            'description' => $request->description,
        ];

        $result = $this->winning->insert($data);

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

    public function update($winning_id)
    {
        $request = parse_post_data();

        $data = [
            'winning'     => $request->winning,
            'description' => $request->description,
        ];

        $result = $this->winning->update($data, ['id' => $winning_id]);

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

        $result = $this->winning->delete($data);

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