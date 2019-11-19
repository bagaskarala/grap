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

    public function checkTable($table)
    {
        if ($table == null) {
            $table = $this->table;
        }
        return $table;
    }

    public function count($table = null)
    {
        $table = $this->checkTable($table);
        return $this->db->count_all_results($table);
    }

    public function get_all_array($table = null)
    {
        $table = $this->checkTable($table);
        return $this->db->get($table)->result_array();
    }

    public function get_single_array($table = null)
    {
        $table = $this->checkTable($table);
        return $this->db->get($table)->row_array();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_single()
    {
        return $this->db->get($this->table)->row();
    }

    public function get_where($where, $table = "")
    {
        $table = $this->checkTable($table);
        return $this->db->get_where($table, $where)->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        // jika sukses kembalikan array data
        if ($this->db->insert_id()) {
            return $data;
        } else {
            return false;
        }
    }

    public function update($data, $where, $table = null)
    {
        $table = $this->checkTable($table);

        // cek dulu apakah datanya ada
        if ($this->db->get_where($table, $where)->num_rows() == 0) {
            return false;
        }

        // jika sukses kembalikan array data
        if ($this->db->update($table, $data, $where)) {
            return $data;
        } else {
            return false;
        }
    }

    public function delete($where, $table = null)
    {
        $table = $this->checkTable($table);
        $this->db->delete($table, $where);
        return $this->db->affected_rows();
    }

    public function where($column, $condition)
    {
        $this->db->where($column, $condition);
        return $this;
    }

    public function validate($data = null)
    {
        // validasi form
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="m-0">', '</p>');

        // jika diberikan array, maka memvalidasi array tersebut
        // jika tidak maka validasi langsung dari $_POST
        if ($data) {
            $this->form_validation->set_data($data);
        }

        // rules validasi ada di tiap model
        $validationRules = $this->getValidationRules();
        $this->form_validation->set_rules($validationRules);

        // true or false
        return $this->form_validation->run();
    }

    public function order_by($column, $order = 'asc')
    {
        $this->db->order_by($column, $order);
        return $this;
    }

    public function join($table, $type = 'left')
    {
        $this->db->join($table, "$this->table.{$table}_id = $table.id", $type);
        return $this;
    }

    public function join_table($table_to, $table_from, $type = 'left')
    {
        $this->db->join($table_to, "$table_from.{$table_to}_id = $table_to.id", $type);
        return $this;
    }

    public function select($columns)
    {
        $this->db->select($columns);
        return $this;
    }

    public function like($column, $condition)
    {
        $this->db->like($column, $condition);
        return $this;
    }

    public function or_like($column, $condition)
    {
        $this->db->or_like($column, $condition);
        return $this;
    }
}

/* End of file MY_Model.php */