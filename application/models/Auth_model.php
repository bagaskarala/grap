<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends MY_Model
{
    public function getValidationRules()
    {
        // rule validasi untuk register
        $validationRules = [
            [
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required|trim',
            ],
            [
                'field'  => 'email',
                'label'  => 'Email',
                'rules'  => 'required|trim|valid_email|is_unique[user.email]',
                'errors' => [
                    'is_unique' => 'This email has already registered',
                ],
            ],
            [
                'field'  => 'password1',
                'label'  => 'Password',
                'rules'  => 'required|trim|min_length[5]|matches[password2]',
                'errors' => [
                    'matches'    => 'Password dont match',
                    'min_length' => 'Password too short',
                ],
            ],
            [
                'field'  => 'password2',
                'label'  => 'Password',
                'rules'  => 'required|trim|matches[password1]',
                'errors' => [
                    'matches' => '',
                ],
            ],
        ];

        return $validationRules;
    }

    public function login($login_data)
    {
        // mencari email di databse
        $user = $this->db->get_where('user', ['email' => $login_data['login_email']])->row_array();

        // jika user ada
        if ($user) {
            // jika user active
            if ($user['is_active'] == 1) {
                if (password_verify($login_data['login_password'], $user['password'])) {

                    $this->select('setting.*,city.city');
                    $this->join_table('city', 'setting');
                    $setting = $this->get_single_array('setting');

                    $data = [
                        'email'           => $user['email'],
                        'role_id'         => $user['role_id'],
                        'name'            => $user['name'],
                        'image'           => $user['image'],
                        'setting_city_id' => $setting ? $setting['city_id'] : null,
                        'setting_city'    => $setting ? $setting['city'] : null,
                        'setting_year'    => $setting ? $setting['year'] : null,
                        'setting_month'   => $setting ? $setting['month'] : null,
                        'login'           => true,
                    ];
                    // set session, masuk ke app
                    $this->session->set_userdata($data);

                    return [
                        'status'  => true,
                        'message' => 'Login Success',
                    ];
                } else {
                    // jika password salah
                    return [
                        'status'  => false,
                        'message' => 'Wrong Password',
                    ];
                }
            } else {
                // jika is_active = 0
                return [
                    'status'  => false,
                    'message' => 'Account has not been activated',
                ];
            }
        } else {
            // jika email tidak terdaftar
            return [
                'status'  => false,
                'message' => 'Email has not been registered',
            ];
        }
    }

    public function register($register_data)
    {
        $data = [
            'name'         => htmlspecialchars($register_data['name']),
            'email'        => htmlspecialchars($register_data['email']),
            'image'        => 'default.jpg',
            'password'     => password_hash($register_data['password1'], PASSWORD_DEFAULT),
            'role_id'      => 2,
            'is_active'    => 1,
            'date_created' => time(),
        ];

        // insert data
        if ($this->db->insert('user', $data)) {
            return true;
        } else {
            return false;
        }

    }

    public function logout()
    {
        // reset session
        $data = [
            'name'            => null,
            'email'           => null,
            'image'           => null,
            'role_id'         => null,
            'setting_city_id' => null,
            'setting_city'    => null,
            'setting_year'    => null,
            'setting_month'   => null,
            'login'           => false,
        ];
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
    }
}

/* End of file Auth_model.php */