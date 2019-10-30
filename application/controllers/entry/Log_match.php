<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_match extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Log Match';
        $data['page']  = 'entry/log_match';

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $log_matchs = $this->log_match->get_all_log_match();

        if (count($log_matchs) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($log_matchs) {
            return $this->send_json_output($log_matchs, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'division_id'  => $request->division_id,
            'pd1_id'       => $request->pd1_id,
            'pd2_id'       => $request->pd2_id,
            'match_system' => $request->match_system,
        ];

        // validasi
        if ($this->log_match->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->log_match->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($log_match_id)
    {
        $request = parse_post_data();

        $data = [
            'division_id'  => $request->division_id,
            'pd1_id'       => $request->pd1_id,
            'pd2_id'       => $request->pd2_id,
            'match_system' => $request->match_system,
        ];

        // validasi
        if ($this->log_match->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->log_match->update($data, ['id' => $log_match_id]);
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

        $result = $this->log_match->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }

    public function filter_division($division_id)
    {
        if ($division_id === 'null') {
            $result = $this->log_match->get_all_log_match();
        } else {
            $result = $this->log_match->filter_division($division_id);
        }

        if (count($result) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed filter data", false, 400);
        }
    }

    public function generate_schedule()
    {
        $request = parse_post_data();

        $result = $this->log_match->generate_schedule($request->division_id, $request->match_system);

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
        }
    }

    public function reset_schedule()
    {
        $request = parse_post_data();

        $result = $this->log_match->reset_schedule($request->division_id);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Reset Schedule", false, 400);
        }
    }
};