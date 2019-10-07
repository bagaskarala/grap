<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$data['user'] = $this->db->get_where('user', ['name'=> $this->session->userdata('name')])->row_array();
		echo 'Selamat datang' . $data['user']['name'];


	}


}