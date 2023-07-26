<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Produk extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Api_model', 'api');
        $this->load->model('Base_model',    'base');
        $this->load->library('form_validation');
    }

    //Menampilkan data produk
    function index_get()
    {
        $id = $this->get('id');
        if ($id === NULL) {
            $produk = $this->api->get('produks')->result();
        } else {
            $produk = $this->api->get('produks', $id)->result();
        }

        if ($produk) {
            $this->response([
                'success'    => 1,
                'message'    => 'Get Produk Berhasil',
                'produks'     => $produk
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success'    => 0,
                'message'   => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //Masukan function selanjutnya disini
}
