<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]');

		if ($this->form_validation->run() == false) {
			$this->load->view('templates/auth_header');
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		$name = $this->input->post('name');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['name' => $name])->row_array();

		if ($user) { //jika user ada
			if ($user['is_active'] == 1) {
				if (password_verify($password, $user['password'])) {
					$data = [
						'name' => $user['name'],
						'role_id' => $user['role_id'],
					];
					$this->session->set_userdata($data);
					redirect('user');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
		  Password Salah
		</div>');
					redirect('auth');
				}
			}
		} else { //jika ga ada name
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
		  Username Belum Didaftarkan!!
		</div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules(
			'password1',
			'Password',
			'required|trim|min_length[5]|max_length[12]|matches[password2]',

			[
				'matches' => 'Password tidak cocok',
				'min_length' => 'Password Kurang Panjang',
			]
		);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false) { //dibuat manggil index gimana ya
			$this->load->view('auth/registration');
		} else {
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'image' => 'default.jpg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 1,
				'date_created' => time(),

			];

			$this->db->insert('user', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		  Selamat! Akun anda sudah didaftarkan! Silahkan bisa Login
		</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		  Anda Berhasil Logout
		</div>');
		redirect('auth');
	}
}