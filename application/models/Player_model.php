<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Player_model extends MY_Model
{
    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'country_id',
                'label' => 'country',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'club_id',
                'label' => 'club',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'nickname',
                'label' => 'nickname',
                'rules' => 'trim',
            ],
            [
                'field' => 'gender',
                'label' => 'gender',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'img',
                'label' => 'img',
                'rules' => 'trim',
            ],
            [
                'field' => 'height',
                'label' => 'height',
                'rules' => 'numeric|trim',
            ],
            [
                'field' => 'weight',
                'label' => 'weight',
                'rules' => 'numeric|trim',
            ],
            [
                'field' => 'achievement',
                'label' => 'achievement',
                'rules' => 'integer|trim',
            ],
        ];

        return $validationRules;
    }

    public function get_all_player()
    {
        $this->db->select("$this->table.*, club.club, country.country");
        $this->join('club');
        $this->join('country');
        $this->order_by('gender');
        $this->order_by('name');
        return $this->get_all_array();
    }

    public function filter($division_id, $min_weight, $max_weight)
    {
        if (!$min_weight) {
            $min_weight = 0;
        }
        if (!$max_weight) {
            $max_weight = 200;
        }

        $division = $this->get_where(['id' => $division_id], 'division');

        // get filtered player
        $this->select("$this->table.*, club.club, country.country");
        $this->join('club');
        $this->join('country');
        $this->where('weight >', $min_weight);
        $this->where('weight <', $max_weight);
        if ($division['gender'] != 'all') {
            $this->where('gender', $division['gender']);
        }
        $this->order_by('name');
        $this->order_by('weight');
        return $this->get_all_array();
    }

    public function search($keyword)
    {
        $this->db->select("$this->table.*, club.club, country.country");
        $this->join('club');
        $this->join('country');
        $this->like('player.name', $keyword);
        $this->or_like('player.nickname', $keyword);
        $this->or_like('country.country', $keyword);
        $this->or_like('club.club', $keyword);
        $this->order_by('gender');
        $this->order_by('name');
        return $this->get_all_array();
    }

    public function upload_photo($id, $photo_type)
    {
        $data_player = $this->db->get_where('player', ['id' => $id])->row_array();

        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '10000';
        $config['upload_path']   = './assets/img/player';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($photo_type)) {

            $old_image = $data_player[$photo_type];
            if ($old_image != 'default.jpg' && $old_image != null) {
                if (file_exists(FCPATH . 'assets/img/player/' . $old_image)) {
                    unlink(FCPATH . 'assets/img/player/' . $old_image);
                }
            }
            $new_image = $this->upload->data('file_name');
            $this->db->set($photo_type, $new_image);
            $this->db->where('id', $id);
            $response = $this->db->update('player');

            if ($response) {
                $result = [
                    'status' => true,
                    'data'   => 'success update photo',
                ];
            } else {
                $result = [
                    'status'  => false,
                    'message' => 'failed update photo',
                ];
            }

        } else {
            $result = [
                'status'  => false,
                'message' => $this->upload->display_errors(),
            ];
        }

        return $result;
    }
}

/* End of file Player_model.php */