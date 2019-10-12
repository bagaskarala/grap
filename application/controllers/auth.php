<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // untuk validasi form login dan register
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $data['title'] = "Login";
        $data['page']  = "auth/login";

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth', $data);
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        // private method, hanya dapat di akses di class Auth
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // jika user ada
        if ($user) {
            // jika user active
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email'   => $user['email'],
                        'role_id' => $user['role_id'],
                    ];
                    // set session
                    $this->session->set_userdata($data);
                    redirect('user');
                } else {
                    $this->session->set_flashdata('message', 'Wrong password');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', 'Account has not been activated');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', 'Email has not been registered');
            redirect('auth');
        }
    }

    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $data['title'] = "Registration";
        $data['page']  = 'auth/registration';

        // set rule
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|trim|valid_email|is_unique[user.email]',
            [
                'is_unique' => 'This email has already registered',
            ]
        );
        $this->form_validation->set_rules(
            'password1',
            'Password',
            'required|trim|min_length[5]|matches[password2]',
            [
                'matches'    => 'Password dont match',
                'min_length' => 'Password too short',
            ]
        );
        $this->form_validation->set_rules(
            'password2',
            'Password',
            'required|trim|matches[password1]',
            [
                'matches' => '',
            ]
        );

        // jalankan validasi
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth', $data);
        } else {
            // kumpulkan data dari form
            $data = [
                'name'         => htmlspecialchars($this->input->post('name', true)),
                'email'        => htmlspecialchars($this->input->post('email')),
                'image'        => 'default.jpg',
                'password'     => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id'      => 2,
                'is_active'    => 1,
                'date_created' => time(),
            ];

            // insert data
            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', 'Register Sucessfully, Now you can login to your account');
            // redirect ke login
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', 'Logout successfuly');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');

    }

    public function checkInsert()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request      = json_decode($stream_clean);
        // $menu = $this->input->post('menu');

        $result = $this->db->insert('user_menu', ['menu' => $request->menu]);

        if (!$this->db->affected_rows()) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'gagal insert',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'success' => true,
                    'message' => $result,
                ]));
        }
    }
}