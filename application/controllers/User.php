<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        if (!is_admin()) {
            redirect('dashboard');
        }
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "User Management";
        $data['users'] = $this->base_model->getUsers(userdata('id_user'));
        $this->template->load('template', 'user/data', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($mode == 'add') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
            $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        } else {
            $db = $this->base_model->getUser('user', ['id_user' => $this->input->post('id_user', true)]);
            $username = $this->input->post('username', true);
            $email = $this->input->post('email', true);

            $uniq_username = $db['username'] == $username ? '' : '|is_unique[user.username]';
            $uniq_email = $db['email'] == $email ? '' : '|is_unique[user.email]';

            $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $uniq_email);
        }
    }

    public function add()
    {
        $data['title'] = "Tambah User";
        $this->template->load('template', 'user/add', $data);
    }

    public function proses()
    {
        $input = $this->input->post(null, true);

        $input_data = [
            'name'  => $input['nama'],
            'username'      => $input['username'],
            'email'         => $input['email'],
            'no_telp'       => $input['no_telp'],
            'role'          => $input['role'],
            'password'      => password_hash($input['password'], PASSWORD_DEFAULT),
            'created_at'    => time(),
            'foto'          => 'user.png',
            'is_active'     => 1
        ];


        if ($this->base_model->insert('user', $input_data)) {
            set_pesan('data berhasil disimpan.');
            redirect('user');
        } else {
            set_pesan('data gagal disimpan', false);
            redirect('user/add');
        }
    }

    public function add2()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|trim');
        $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        $this->form_validation->set_rules('name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Tambah User";
            $this->template->load('template', 'user/add', $data);
        } else {
            $input = $this->input->post(null, true);

            $input_data = [
                'name'  => $input['nama'],
                'username'      => $input['username'],
                'email'         => $input['email'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role'],
                'password'      => password_hash($input['password'], PASSWORD_DEFAULT),
                'created_at'    => time(),
                'foto'          => 'user.png'
            ];


            if ($this->base_model->insert('user', $input_data)) {
                set_pesan('data berhasil disimpan.');
                redirect('user');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('user/add');
            }
        }
    }

    public function edit($id)
    {
        $data['title'] = "Edit User";
        $data['user'] = $this->base_model->getUser('user', ['id_user' => $id]);
        $this->template->load('template', 'user/edit', $data);
    }

    public function prosesEdit()
    {
        $input = $this->input->post(null, true);
        $input_data = [
            'name'  => $input['nama'],
            'username'      => $input['username'],
            'email'         => $input['email'],
            'no_telp'       => $input['no_telp'],
            'role'          => $input['role']
        ];

        if ($this->base_model->update('user', 'id_user', $input['id_user'], $input_data)) {
            set_pesan('data berhasil diubah.');
            redirect('user');
        } else {
            set_pesan('data gagal diubah.', false);
            redirect('user/edit/' . $input['id_user']);
        }
    }

    public function edit2($id)
    {
        // $id = encode_php_tags($getId);
        $this->_validasi('edit');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit User";
            $data['user'] = $this->base_model->getUser('user', ['id_user' => $id]);
            var_dump($data['user']);
            // $this->template->load('template', 'user/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $input_data = [
                'name'  => $input['nama'],
                'username'      => $input['username'],
                'email'         => $input['email'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role']
            ];

            if ($this->base_model->update('user', 'id_user', $id, $input_data)) {
                set_pesan('data berhasil diubah.');
                redirect('user');
            } else {
                set_pesan('data gagal diubah.', false);
                redirect('user/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        // $id = encode_php_tags($getId);
        if ($this->base_model->delete('user', 'id_user', $getId)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('user');
    }

    public function toggle($getId)
    {
        $status = $this->base_model->getUser('user', ['id_user' => $getId])['is_active'];
        $toggle = $status ? 0 : 1;
        $pesan = $toggle ? 'user diaktifkan.' : 'user dinonaktifkan.';

        if ($this->base_model->update('user', 'id_user', $getId, ['is_active' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('user');
    }
}
