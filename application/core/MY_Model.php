<?php
defined('BASEPATH') or exit('No direct script access allowed');

// semua fungsi disini dapat digunakan oleh semua class yang extends MY_Model
class MY_Model extends CI_Model
{
    protected $table = '';

    public function __construct()
    {
        parent::__construct();

        // default table use model name
        if (!$this->table) {
            $this->table = strtolower(str_replace('_model', '', get_class($this)));
        }
    }

    public function get_all_array()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_single_array()
    {
        return $this->db->get($this->table)->row_array();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_single()
    {
        return $this->db->get($this->table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        $this->db->update($this->table, $data, $where);
        return $this;
    }

    public function delete($where)
    {
        $this->db->delete($this->table, $where);
        return $this->db->affected_rows();
    }

    public function where($column, $condition)
    {
        $this->db->where($column, $condition);
        return $this;
    }

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