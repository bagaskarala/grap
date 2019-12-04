<?php
defined('BASEPATH') or exit('No direct script access allowed');

class City extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'City';
        $data['page']  = 'master/city';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $cities = $this->city->get_all_array();

        if (count($cities) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($cities) {
            return $this->send_json_output($cities, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'city' => $request->city,
        ];

        // validasi
        if ($this->city->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->city->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($city_id)
    {
        $request = parse_post_data();

        $data = [
            'city' => $request->city,
        ];

        // validasi
        if ($this->city->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->city->update($data, ['id' => $city_id]);
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

        $result = $this->city->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};