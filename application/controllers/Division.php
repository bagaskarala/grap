<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Division extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Division';
        $data['page']  = 'master/division';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $divisions = $this->division->get_all_array();

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data'    => $divisions,
            ]));
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'division'   => $request->division,
            'system'     => $request->system,
            'keterangan' => $request->keterangan,
            'play'       => $request->play,
        ];

        $result = $this->division->insert($data);

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

    public function update($division_id)
    {
        $request = parse_post_data();

        $data = [
            'division'   => $request->division,
            'system'     => $request->system,
            'keterangan' => $request->keterangan,
            'play'       => $request->play,
        ];

        // $result = $this->division->insert($data);
        $result = $this->division->update($data, ['id' => $division_id]);

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

        $result = $this->division->delete($data);

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