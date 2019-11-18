<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sub_menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = "Submenu Management";
        $data['page']  = "menu/sub_menu";

        $this->load->view('templates/app', $data);
    }

    public function get_all()
    {
        $sub_menus = $this->sub_menu->get_sub_menu();

        if (count($sub_menus) == 0) {
            return $this->send_json_output([], true, 200);
        } else if ($sub_menus) {
            return $this->send_json_output($sub_menus, true, 200);
        } else {
            return $this->send_json_output("Failed get data", false, 400);
        }
    }

    public function insert()
    {
        $request = parse_post_data();

        $data = [
            'title'     => $request->title,
            'menu_id'   => $request->menu_id,
            'url'       => $request->url,
            'icon'      => $request->icon,
            'is_active' => $request->is_active,
        ];

        // validasi
        if ($this->sub_menu->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->sub_menu->insert($data);
        }

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Insert Data", false, 400);
        }
    }

    public function update($sub_menu_id)
    {
        $request = parse_post_data();

        $data = [
            'title'     => $request->title,
            'menu_id'   => $request->menu_id,
            'url'       => $request->url,
            'icon'      => $request->icon,
            'is_active' => $request->is_active,
        ];

        // validasi
        if ($this->sub_menu->validate($data) == false) {
            return $this->send_json_output(validation_errors(), false, 422);
        } else {
            $result = $this->sub_menu->update($data, ['id' => $sub_menu_id]);
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

        $result = $this->sub_menu->delete($data);

        if ($result) {
            return $this->send_json_output($result, true, 200);
        } else {
            return $this->send_json_output("Failed Delete Data", false, 400);
        }
    }
};

// <?php
// defined('BASEPATH') or exit('No direct script access allowed');

// class Sub_menu extends MY_Controller
// {
//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function index()
//     {
//         $data['title'] = "Submenu Management";
//         $data['page']  = "menu/sub_menu";
//         // $data['user']  = $this->db->get_where('user', ['email' => $this->session_email])->row_array();

//         $this->load->model('Menu_model', 'menu');
//         $data['submenu'] = $this->sub_menu->get_sub_menu();
//         $data['menu']    = $this->db->get('menu')->result_array();

//         $this->form_validation->set_rules('title', 'Title', 'required');
//         $this->form_validation->set_rules('menu_id', 'Menu', 'required');
//         $this->form_validation->set_rules('url', 'URL', 'required');
//         $this->form_validation->set_rules('icon', 'Icon', 'required');

//         if ($this->form_validation->run() == false) {
//             $this->load->view('templates/app', $data);
//         } else {
//             $data = [
//                 'title'     => $this->input->post('title'),
//                 'menu_id'   => $this->input->post('menu_id'),
//                 'url'       => $this->input->post('url'),
//                 'icon'      => $this->input->post('icon'),
//                 'is_active' => $this->input->post('is_active'),
//             ];

//             $this->db->insert('sub_menu', $data);
//             $this->session->set_flashdata('message', 'Submenu Added');
//             redirect('menu/submenu');
//         }
//     }
// };