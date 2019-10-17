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

        // mengatasi redirect loop di login
        if ($model_classname != 'auth') {
            is_logged_in();
        }
    }

    // generate json output API
    public function send_json_output($result, Bool $status, Int $status_code)
    {
        if ($status) {
            // if success
            $output = [
                'status' => $status,
                'data'   => $result,
            ];
        } else {
            // if error
            $output = [
                'status'  => $status,
                'message' => $result,
            ];
        };
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }
}