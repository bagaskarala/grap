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

        // auto set to draw, ketika finished tapi tidak milih winner
        if (($request->match_status == 2 and $request->winner == null)) {
            $request->winner = 0;
        }

        // dynamic data input dari logmatch detail and logmatch list
        // ubah object jadi array untuk ke database
        $data = [];
        foreach ($request as $key => $value) {
            $data[$key] = $value;
        }

        // error jika ada winner tapi match belum finished
        if (($request->match_status != 2 and $request->winner > 0)) {
            $result = [
                'status'  => false,
                'message' => 'Set match status finished and choose the winner',
            ];
        } else {
            // validasi
            if ($this->log_match->validate($data) == false) {
                return $this->send_json_output(validation_errors(), false, 422);
            } else {
                $response = $this->log_match->update($data, ['id' => $log_match_id]);
            }

            // jika update data success dan ada winner,
            // maka isi tabel next match
            if ($response and $request->match_status == 2 and $request->winner > 0) {
                $result = $this->log_match->next_play($log_match_id);
            } else {
                $result = [
                    'status' => true,
                    'data'   => 'Success update without next play',
                ];
            }
        }

        if ($result['status']) {
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
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
        // menyimpan pilihan divisi di session
        $this->session->set_userdata(['division_id' => $division_id]);

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

        // demo
        // $request               = new stdClass();
        // $request->division_id  = 11;
        // $request->match_system = 'roundrobin';

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

    public function generate_player()
    {
        $request = parse_post_data();

        $result = $this->log_match->generate_player($request->division_id, $request->match_system);

        if ($result['status']) {
            // start play, pemain yang ga punya musuh langsung next ke match berikutnya
            $this->log_match->start_play($request->division_id);
            return $this->send_json_output($result['data'], true, 200);
        } else {
            return $this->send_json_output($result['message'], false, 400);
        }
    }

    public function reset_player()
    {
        $request = parse_post_data();

        $result = $this->log_match->reset_player($request->division_id);

        return $result;
    }

    public function detail()
    {
        $data['title'] = 'Log Match';
        $data['page']  = 'entry/log_match_detail';

        $this->load->view('templates/app', $data);
    }

    public function get_detail($log_match_id)
    {
        $log_matchs = $this->log_match->get_detail_log_match($log_match_id);

        if (count($log_matchs) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($log_matchs) {
            return $this->send_json_output($log_matchs, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }
};