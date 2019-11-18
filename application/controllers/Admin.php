<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->session_email = $this->session->userdata('email');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page']  = 'admin/index';

        $data['user'] = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

        $this->load->view('templates/app', $data);
    }

    public function api_get_all_users()
    {
        $users = $this->db->get('user')->result();

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'message' => $users,
            ]));
    }

    public function role()
    {
        $data['title'] = 'Role';
        $data['page']  = 'admin/role';

        $data['user'] = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

        $data['role'] = $this->db->get('role')->result_array();

        $this->load->view('templates/app', $data);
    }

    public function role_access($role_id)
    {
        $data['title'] = "Role";
        $data['page']  = "admin/role_access";

        $data['user'] = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

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
};