<?php

// mengecek user yang login dicocokan dengan tabel menu
function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        // cek uri pertama
        $role_id = $ci->session->userdata('role_id');
        $menu    = $ci->uri->segment(1);

        // cek di db
        $query_menu = $ci->db->get_where('menu', ['menu' => $menu])->row_array();
        $menu_id    = $query_menu['id'];

        // cari di tabel access_menu
        $user_access = $ci->db->get_where('access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id,
        ]);

        if ($user_access->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }

}

// cek akses role pada menu role_access
function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('menu_id', $menu_id);
    $ci->db->where('role_id', $role_id);
    $result = $ci->db->get('access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

// mengambil post data dari client
function parse_post_data()
{
    $ci           = get_instance();
    $stream_clean = $ci->security->xss_clean($ci->input->raw_input_stream);
    return json_decode($stream_clean);
}