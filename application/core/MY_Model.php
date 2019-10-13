<?php
defined('BASEPATH') or exit('No direct script access allowed');

// semua fungsi disini dapat digunakan oleh semua class yang extends MY_Model
class MY_Model extends CI_Model
{
    public function validate()
    {
        // validasi form
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="small text-danger m-0">', '</p>');

        // rules validasi ada di tiap model
        $validationRules = $this->getValidationRules();
        $this->form_validation->set_rules($validationRules);

        return $this->form_validation->run();
    }
}

/* End of file MY_Model.php */