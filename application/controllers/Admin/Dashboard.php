<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page']  = 'admin/dashboard';

        $this->load->view('templates/app', $data);
    }

    public function get_all_users()
    {
        $users = $this->dashboard->get_all_users();

        if (count($users) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($users) {
            return $this->send_json_output($users, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function delete_user()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->dashboard->delete($data, 'user');

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};