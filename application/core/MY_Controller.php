<?php
defined('BASEPATH') or exit('No direct script access allowed');

// semua fungsi disini dapat digunakan oleh semua class yang extends MY_Model
class MY_Controller extends CI_Controller
{
    protected $_name    = '';
    protected $_email   = '';
    protected $_role_id = '';
    protected $_login   = '';
    protected $page     = '';

    public function __construct()
    {
        parent::__construct();

        // set global var
        // hanya bisa digunakan di class yang extend class ini
        $this->_name    = $this->session->userdata('name');
        $this->_email   = $this->session->userdata('email');
        $this->_role_id = $this->session->userdata('role_id');
        $this->_login   = $this->session->userdata('login');

        // auto load model
        $model_classname = strtolower(get_class($this));
        $model_filename  = ucwords(get_class($this));
        if (file_exists(APPPATH . 'models/' . $model_filename . '_model.php')) {
            $this->load->model($model_classname . '_model', $model_classname, true);
        }

        is_logged_in();
    }
}