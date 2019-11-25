<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'User';
        $data['page']  = 'admin/user';

        $this->load->view('templates/app', $data);
    }

    public function get_all_users()
    {
        $users = $this->user->get_all_users();

        if (count($users) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($users) {
            return $this->send_json_output($users, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function update($user_id)
    {
        $request = parse_post_data();

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'is_active' => $request->is_active,
        ];

        // validasi
        if ($this->user->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->user->update($data, ['id' => $user_id]);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Update Data", false, 400);
        }
    }

    public function delete_user()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->user->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};