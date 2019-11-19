<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Role extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Role';
        $data['page']  = 'admin/role';

        $data['role'] = $this->db->get('role')->result_array();

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $roles = $this->role->get_all_array();

        if (count($roles) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($roles) {
            return $this->send_json_output($roles, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'role' => $request->role,
        ];

        // validasi
        if ($this->role->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->role->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($role_id)
    {
        $request = parse_post_data();

        $data = [
            'role' => $request->role,
        ];

        // validasi
        if ($this->role->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->role->update($data, ['id' => $role_id]);
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

        $result = $this->role->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }

    public function role_access($role_id)
    {
        $data['title'] = "Role";
        $data['page']  = "admin/role_access";

        $data['role'] = $this->db->get_where('role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('menu')->result_array();

        $this->load->view('templates/app', $data);
    }

    public function change_access()
    {
        $menu_id = $this->input->post('menu_id');
        $role_id = $this->input->post('role_id');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id,
        ];
        $result = $this->db->get_where('access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('access_menu', $data);
        } else {
            $this->db->delete('access_menu', $data);
        }

        $this->session->set_flashdata('message', 'Access changed');
    }

}

/* End of file Role.php */