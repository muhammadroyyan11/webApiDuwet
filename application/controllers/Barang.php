<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        date_default_timezone_set('Asia/Jakarta');
        // // $this->load->model('Auth_model', 'auth');
        // $this->load->model('Admin_model', 'admin');
        $this->load->model('Base_model', 'base');
        $this->load->model('Barang_model', 'barang');
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Barang',
            'barang'    => $this->barang->get()->result_array(),
            'kategori'  => $this->base->get('kategori')->result_array()
        ];
        $this->template->load('template', 'barang/data', $data);
    }

    public function prosesAdd()
    {
        $post = $this->input->post(null, true);

        $config['upload_path']          = './uploads/produk/';
        $config['allowed_types']        = 'jpg|jpeg|png|gif|';
        $config['max_size']             = 10000;
        $config['max_width']            = 10000;
        $config['file_name']            = 'produks-' . date('ymd') . '-' . substr(md5(rand()), 0, 6);

        // var_dump($post);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambarFile')) {
            $post['gambarFile'] = $this->upload->data('file_name');
            $post['tipe_file'] = $this->upload->data('file_type');

            $params = [
                'name'          => $post['nama'],
                'harga'         => $post['harga'],
                'stok'          => $post['stok'],
                'deskripsi'     => $post['deskripsi'],
                'image'         => $post['gambarFile'],
                'kategori_id'   => $post['kategori'],
                'created_at'    => date('Y-m-d h:i:sa'),
            ];

            $this->base->add('produks', $params);

            if ($this->db->affected_rows() > 0) {
                set_pesan('Berita berhasil di tambahkan');
            } else {
                set_pesan('Gagal menyimpan Berita, silahkan coba kembali', FALSE);
            }
        } else {
            echo 'error';
        }
        redirect('barang');
    }

    public function delete($id)
    {
        $this->base->del('produks', ['id' => $id]);

        if ($this->db->affected_rows() > 0) {
            set_pesan('Data berhasil dihapus');
        } else {
            set_pesan('Terjadi Kesalahan, Harap Coba Kembali', FALSE);
        }

        redirect('barang');
        
    }
}
