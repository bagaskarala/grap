<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Division extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Division';
        $data['page']  = 'master/division';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $divisions = $this->division->get_all_array();

        if (count($divisions) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($divisions) {
            return $this->send_json_output($divisions, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'division'    => $request->division,
            'min_weight'  => $request->min_weight,
            'max_weight'  => $request->max_weight,
            'gender'      => $request->gender,
            'system'      => $request->system,
            'description' => $request->description,
            'play'        => $request->play,
        ];

        // validasi
        if ($this->division->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->division->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($division_id)
    {
        $request = parse_post_data();

        $data = [
            'division'    => $request->division,
            'min_weight'  => $request->min_weight,
            'max_weight'  => $request->max_weight,
            'gender'      => $request->gender,
            'system'      => $request->system,
            'description' => $request->description,
            'play'        => $request->play,
        ];

        if ($this->division->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->division->update($data, ['id' => $division_id]);
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

        $result = $this->division->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};