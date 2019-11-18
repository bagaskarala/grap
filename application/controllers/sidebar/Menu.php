<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = "Menu Management";
        $data['page']  = "menu/menu";

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $menus = $this->menu->get_all_array();

        if (count($menus) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($menus) {
            return $this->send_json_output($menus, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'menu' => $request->menu,
        ];

        // validasi
        if ($this->menu->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->menu->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($menu_id)
    {
        $request = parse_post_data();

        $data = [
            'menu' => $request->menu,
        ];

        // validasi
        if ($this->menu->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->menu->update($data, ['id' => $menu_id]);
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

        $result = $this->menu->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};