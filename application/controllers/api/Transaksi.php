<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Transaksi extends REST_Controller
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
    function index_get($id)
    {
        $transaksi = $this->base->get('transaksis', ['user_id' => $id])->result();

        // $transaksi_detail = $this->base->getDetail(['transaksi_id' => ]);

        foreach ($transaksi as $key => $data) {
            $details = $this->base->getDetail(['transaksi_id' => $data->id])->result();
            foreach ($details as $detail) {
                $detail->produk_id;
            }
            // var_dump($details);
        }

        $newCollection = [
            'transaksi' => [
                $transaksi
            ]
        ];
        var_dump($newCollection);

        // if ($produk) {
        //     $this->response([
        //         'success'    => 1,
        //         'message'    => 'Get History',
        //         'history'     => collect($transaksi)
        //     ], REST_Controller::HTTP_OK);
        // } else {
        //     $this->response([
        //         'success'    => 0,
        //         'message'   => 'Data not found'
        //     ], REST_Controller::HTTP_NOT_FOUND);
        // }
    }

    function checkout_post()
    {
        $datetime = new DateTime(date('Y-m-d'));
        $datetime->modify('+2 day');
        $newTime = $datetime->format('Y-m-d');

        $kode_payment = "INV/PYM/" . date('Y-m-d') . "/" . rand(100, 999);
        $kode_trx = "INV/PYM/" . date('Y-m-d') . "/" . rand(100, 999);
        $kode_unik = rand(100, 999);
        $status = "MENUNGGU";

        $expired_at = $newTime;

        $paramsTransaksi = [
            'user_id' =>  $this->post('user_id'),
            'total_item' => $this->post('total_item'),
            'total_harga' => $this->post('total_harga'),
            'name' => $this->post('name'),
            'jasa_pengiriaman' => $this->post('jasa_pengiriaman'),
            'ongkir' => $this->post('ongkir'),
            'total_transfer' => $this->post('total_transfer'),
            'bank' => $this->post('bank'),
            'phone' => $this->post('phone'),
            'kode_payment' => $kode_payment,
            'kode_trx' => $kode_trx,
            'kode_unik' => $kode_unik,
            'status' => $status,
            'expired_at' => $expired_at
        ];

        $arrayProduks = new stdClass();
        $arrayProduks = $this->post('produks');

        // var_dump($arrayProduks);



        $return_id = $this->base->insert('transaksis', $paramsTransaksi);

        if ($this->db->affected_rows() > 0) {
            foreach ($arrayProduks as $produk) {
                $detail = [
                    'transaksi_id' => $return_id,
                    'produk_id' => $produk['id'],
                    'total_item' => $produk['total_item'],
                    'catatan' => $produk['catatan'],
                    'total_harga' => $produk['total_harga']
                ];
                $this->base->insert('transaksi_details', $detail);
            }
            $this->response([
                'success' => 1,
                'message' => 'Transaksi berhasil dibuat',
                'id_transaksi' => $return_id
            ], REST_Controller::HTTP_OK);
        }
    }
    //Masukan function selanjutnya disini
}
