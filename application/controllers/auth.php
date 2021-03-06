<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($action = null)
    {
        // jika sudah punya sesi, arahkan ke profile
        if ($this->_login) {
            redirect('profile');
        }

        if (!$_POST) {
            $data['title'] = "Login";
            $data['page']  = "auth/login";
            $this->load->view('templates/auth', $data);
        } else {
            if ($action == 'login') {
                $this->_login();
            } elseif ($action == 'registration') {
                $this->registration();
            }
        }
    }

    private function _login()
    {
        // get all form data
        $login_data = $this->input->post(null, true);

        $result = $this->auth->login($login_data);

        if ($result['status']) {
            redirect('profile');
        } else {
            $this->session->set_flashdata('message', $result['message']);
            redirect('auth#login');
        }
    }

    public function registration()
    {
        if ($this->_login) {
            redirect('profile');
        }

        // jalankan validasi
        if ($this->auth->validate() == false) {
            $this->session->set_flashdata('message', validation_errors());
            redirect('auth#register');
        } else {
            // kumpulkan data dari form
            $register_data = $this->input->post(null, true);

            $result = $this->auth->register($register_data);
            if ($result) {
                $this->session->set_flashdata('message', 'Register Sucessfully, Now you can login to your account');
                // redirect ke login
                redirect('auth#login');
            }

        }
    }

    public function logout()
    {
        $this->auth->logout();
        $this->session->set_flashdata('message', 'Logout successfuly');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }
}