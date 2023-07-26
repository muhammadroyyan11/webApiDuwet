<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Base_model', 'base');
    }

    public function index()
    {
        $data['title'] = "Kategori";
        $data = [
          'title'       => 'Kategori',
          'kategori'    => $this->db->get('kategori')->result_array()  
        ];
        $this->template->load('template', 'kategori/data', $data);
    }

    public function add()
    {
        $post = $this->input->post(null, true);

        $params = [
            'nama_kategori'     => $post['nama'],
        ];

        $this->base->add('kategori', $params);

        if ($this->db->affected_rows() > 0) {
            set_pesan('Data berhasil disimpan');
        } else {
            set_pesan('Terjadi Kesalahan, Harap Coba Kembali', FALSE);
        }

        redirect('kategori');
    }

    public function edit($id)
    {
        $post = $this->input->post(null, true);

        $params = [
            'nama_kategori'     => $post['nama']
        ];

        $this->base->edit('kategori', $params, ['id_kategori' => $id]);

        if ($this->db->affected_rows() > 0) {
            set_pesan('Data berhasil disimpan');
        } else {
            set_pesan('Terjadi Kesalahan, Harap Coba Kembali', FALSE);
        }

        redirect('kategori');
    }
    
    public function delete($id)
    {
        $this->base->del('kategori', ['id_kategori' => $id]);

        if ($this->db->affected_rows() > 0) {
            set_pesan('Data berhasil dihapus');
        } else {
            set_pesan('Terjadi Kesalahan, Harap Coba Kembali', FALSE);
        }

        redirect('kategori');
        
    }

}
