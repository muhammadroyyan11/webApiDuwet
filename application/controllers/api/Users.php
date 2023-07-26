<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Api_model', 'api');
        $this->load->model('Base_model', 'base');
        $this->load->library('form_validation');
    }

    //Menampilkan data users
    function index_get()
    {
        $id = $this->get('id');
        if ($id === NULL) {
            $users = $this->api->get('users')->result();
        } else {
            $users = $this->api->get('users', $id)->result();
        }

        if ($users) {
            $this->response([
                'status'    => true,
                'data'      => $users
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //Masukan function selanjutnya disini

    public function login_post()
    {
        $data = array(
            'email'        => $this->post('email'),
            'password'     => $this->post('password')
        );

        $user = $this->api->get('users', ['email' => $data['email']])->row();

        if ($user) {
            $this->response([
                'success'   => 1,
                'message' => 'Selamat datang '.$user->name,
                'user'      => $user
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => 0,
                'message'   => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function register_post()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]', array('is_unique' => 'Email sudah terdaftar.', 'required' => 'Form email wajib diisi'));
        $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[users.phone]', array('is_unique' => 'No Telp sudah terdaftar.', 'required' => 'Form nomer telp wajib diisi'));
       

        if ($this->form_validation->run() === false) {
            // echo validation_errors();
            $this->response([
                'status'    => false,
                'message'   => strip_tags(validation_errors())
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $data = array(
                'name'          => $this->post('name'),
                'email'         => $this->post('email'),
                'password'      => password_hash($this->post('password'), PASSWORD_DEFAULT),
                'phone'         => $this->post('phone'),
            );

            $this->base->add('users', $data);

            if ($this->db->affected_rows() > 0) {
                $this->response([
                    'success' => 1,
                    'message' => 'Selamat datang Register Berhasil'
                ], REST_Controller::HTTP_OK);
            } 
            
        }
    }
}
