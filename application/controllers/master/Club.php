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
        $clubs = $this->club->get_all_array();

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data'    => $clubs,
            ]));
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'club'        => $request->club,
            'description' => $request->description,
        ];

        $result = $this->club->insert($data);

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal insert',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => ['insert_id' => $result],
                ]));
        }
    }

    public function update($club_id)
    {
        $request = parse_post_data();

        $data = [
            'club'        => $request->club,
            'description' => $request->description,
        ];

        // $result = $this->division->insert($data);
        $result = $this->club->update($data, ['id' => $club_id]);

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal update',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => $result,
                ]));
        }
    }

    public function delete()
    {
        $request = parse_post_data();

        $data = [
            'id' => $request->id,
        ];

        $result = $this->club->delete($data);

        if (!$result) {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'gagal delete',
                ]));
        } else {
            return $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => $result,
                ]));
        }
    }
};