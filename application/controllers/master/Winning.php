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

        if (count($winnings) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($winnings) {
            return $this->send_json_output($winnings, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'winning'     => $request->winning,
            'description' => $request->description,
        ];

        // validasi
        if ($this->winning->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->winning->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($winning_id)
    {
        $request = parse_post_data();

        $data = [
            'winning'     => $request->winning,
            'description' => $request->description,
        ];

        // validasi
        if ($this->winning->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->winning->update($data, ['id' => $winning_id]);
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

        $result = $this->winning->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};