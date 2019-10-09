<?php

function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $ci->session->userdata('role_id');
        $menu    = $ci->uri->segment(1);

        $query_menu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id    = $query_menu['id'];

        // cari di tabel user_access_menu
        $user_access = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id,
        ]);

        if ($user_access->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }

}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('menu_id', $menu_id);
    $ci->db->where('role_id', $role_id);
    $result = $ci->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }

}