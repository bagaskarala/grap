<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Club extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Club';
        $data['page']  = 'master/club';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $this->club->order_by('club');
        $clubs = $this->club->get_all_array();

        if (count($clubs) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($clubs) {
            return $this->send_json_output($clubs, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'club'        => $request->club,
            'alias'       => $request->alias,
            'description' => $request->description,
        ];

        // validasi
        if ($this->club->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->club->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($club_id)
    {
        $request = parse_post_data();

        $data = [
            'club'        => $request->club,
            'alias'       => $request->alias,
            'description' => $request->description,
        ];

        // validasi
        if ($this->club->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->club->update($data, ['id' => $club_id]);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Update Data", false, 400);
        }
    }

    public function delete()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->club->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }

    public function search($keyword = null)
    {
        if ($keyword === null) {
            $this->club->order_by('club');
            $result = $this->club->get_all_array();
        } else {
            $result = $this->club->search($keyword);
        }

        if (count($result) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed search data", false, 400);
        }
    }
};