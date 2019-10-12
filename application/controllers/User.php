<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->session_email = $this->session->userdata('email');
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['page']  = 'user/index';
        $data['user']  = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

        $this->load->view('templates/app', $data);
    }

    public function edit()
    {
        $data['title'] = "Edit Profile";
        $data['page']  = "user/edit";
        $data['user']  = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/app', $data);
        } else {
            $name  = $this->input->post('name');
            $email = $this->input->post('email');

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');
            $this->session->set_flashdata('message', 'Profile updated');
            redirect('user/edit');
        }
    }
};