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

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data'    => $countries,
            ]));
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'country'     => $request->country,
            'img'         => $request->img,
            'description' => $request->description,
        ];

        $result = $this->country->insert($data);

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

    public function update($country_id)
    {
        $request = parse_post_data();

        $data = [
            'country'     => $request->country,
            'img'         => $request->img,
            'description' => $request->description,
        ];

        // $result = $this->division->insert($data);
        $result = $this->country->update($data, ['id' => $country_id]);

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

        $result = $this->country->delete($data);

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