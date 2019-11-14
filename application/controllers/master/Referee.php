<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referee extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Referee';
        $data['page']  = 'master/referee';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $referees = $this->referee->get_all_array();

        if (count($referees) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($referees) {
            return $this->send_json_output($referees, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
        ];

        // validasi
        if ($this->referee->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->referee->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($referee_id)
    {
        $request = parse_post_data();

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
        ];

        // validasi
        if ($this->referee->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->referee->update($data, ['id' => $referee_id]);
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

        $result = $this->referee->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};