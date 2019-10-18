<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Country extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Country';
        $data['page']  = 'master/country';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $countries = $this->country->get_all_array();

        if (count($countries) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($countries) {
            return $this->send_json_output($countries, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'country'     => $request->country,
            'img'         => $request->img,
            'description' => $request->description,
        ];

        // validasi
        if ($this->country->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->country->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($country_id)
    {
        $request = parse_post_data();

        $data = [
            'country'     => $request->country,
            'img'         => $request->img,
            'description' => $request->description,
        ];

        // validasi
        if ($this->country->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->country->update($data, ['id' => $country_id]);
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

        $result = $this->country->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};