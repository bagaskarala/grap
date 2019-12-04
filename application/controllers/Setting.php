<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Setting';
        $data['page']  = 'setting/index';

        $this->load->view('templates/app', $data);
    }

    public function get()
    {
        $settings = $this->setting->get_single_array();

        if (count($settings) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($settings) {
            return $this->send_json_output($settings, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function update()
    {
        $request = parse_post_data();

        $data = [
            'year'    => $request->year,
            'month'   => $request->month,
            'city_id' => $request->city_id,
        ];

        // validasi
        if ($this->setting->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->setting->update_setting($data);

            $this->setting->select('setting.*,city.city');
            $this->setting->join_table('city', 'setting');
            $setting = $this->setting->get_single_array('setting');

            $this->session->set_userdata([
                'setting_city'  => $setting['city'],
                'setting_year'  => $setting['year'],
                'setting_month' => $setting['month'],
            ]);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Update Data", false, 400);
        }
    }
};