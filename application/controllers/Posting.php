<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Posting extends CI_Controller
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
        $data = [
            'title'       => 'Posting',
            'berita'    => $this->base->getBerita()->result()
        ];
        $this->template->load('template', 'posting/data', $data);
    }

    public function add()
    {

        if (!$_POST) {
            $input = (object) $this->base_model->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        $data = [
            'title'         => 'Add berita',
            'kategori'      => $this->base_model->getTable('kategori', ['isActive' => 1])->result(),
            'input'         => $input
        ];

        $this->template->load('template', 'posting/add', $data);
    }


    public function prosesAdd()
    {
        $post = $this->input->post(null, true);

        $config['upload_path']          = './assets/uploads/mentah/';
        $config['allowed_types']        = 'jpg|jpeg|png|gif|';
        // $config['max_size']             = 10000;
        // $config['max_width']            = 10000;
        $config['max_height']           = 10000;
        $config['file_name']            = 'foto-' . date('ymd') . '-' . substr(md5(rand()), 0, 6);

        // var_dump($post);
        $this->load->library('upload', $config);

        if (@$_FILES['gambarFile']['name'] != null) {
            if ($this->upload->do_upload('gambarFile')) {
                $post['gambarFile'] = $this->upload->data('file_name');
                $post['tipe_file'] = $this->upload->data('file_type');

                $this->resizeImage($post['gambarFile']);

                $params = [
                    'title' => $post['judul'],
                    'seo_title' => slugify($post['judul']),
                    'konten' => $post['konten'],
                    'gambar_name' => $post['gambarFile'],
                    'featured' => $post['featured'],
                    'thread' => $post['thread'],
                    'choice' => $post['choise'],
                    'user_id' => userdata('id_user'),
                    'kategori_id' => $post['kategori'],
                    'sub_kategori_id' => $post['sub_category'],
                    'author'  => $post['author'],
                    'isActive' => 1,
                    'viewers'   => 1,
                    'date' => date('Y-m-d')
                ];

                $this->base->add('berita', $params);

                if ($this->db->affected_rows() > 0) {
                    set_pesan('Berita berhasil di tambahkan');
                } else {
                    set_pesan('Gagal menyimpan Berita, silahkan coba kembali', FALSE);
                }
            } else {
                echo 'error';
            }
        } else {
            echo 'Testing';
        }

        redirect('posting');
    }
    
    public function preview() {
        $data = [
            'title'     => 'Preview Posting',
            ''
        ];
    }

    public function resizeImage($file_name)
    {
        $source = FCPATH . './assets/uploads/mentah/' .  $file_name;
        $dest   = FCPATH . './assets/uploads/file/';

        $config['image_library']    = 'gd2';
        $config['source_image']     = $source;
        $config['new_image']        = $dest;
        $config['maintain_ratio']   = TRUE;
        $config['width']            = 936;
        $config['height']           = 624;

        $this->load->library('image_lib', $config);


        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
    }

    public function uploadImage()
    {
        if (isset($_FILES['upload']['tmp_name'])) {
            $file = $_FILES['upload']['tmp_name'];
            $fileName = $_FILES['upload']['name'];
            $fileNameArr = explode(".", $fileName);
            $extension = end($fileNameArr);
            $newImageName = 'content-' . date('ymd') . '-' . substr(md5(rand()), 0, 6);
            $allowed = array("jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF");

            if (in_array($extension, $allowed)) {
                move_uploaded_file($file, "./assets/uploads/content/" . $newImageName);
                $functionNumber = $_GET['CKEditorFuncNum'];
                $url = base_url() . "assets/uploads/content/" . $newImageName;
                $message = "";
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($functionNumber, '$url', $message)</script>";
            }
        }
    }

    public function edit($id)
    {
        if (!$_POST) {
            $input = (object) $this->base_model->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        $data = [
            'title'         => 'Edit berita',
            'kategori'      => $this->base_model->getTable('kategori', ['isActive' => 1])->result(),
            'input'         => $input,
            'row'           => $this->base->get('berita', ['id_berita' => $id])->row()
        ];

        $this->template->load('template', 'posting/edit', $data);
    }

    public function get_sub_category()
    {
       $kategori_id = $this->input->post('id', TRUE);

       $data = $this->base_model->getTable('sub_kategori', ['kategori_id' => $kategori_id])->result();

       echo json_encode($data);
    }

    public function prosesEdit()
    {
        $post = $this->input->post(null, true);

        $config['upload_path']          = './assets/uploads/mentah/';
        $config['allowed_types']        = 'jpg|jpeg|png|gif|';
        // $config['max_size']             = 10000;
        // $config['max_width']            = 10000;
        $config['max_height']           = 10000;
        $config['file_name']            = 'foto-' . date('ymd') . '-' . substr(md5(rand()), 0, 6);

        // var_dump($post);
        $this->load->library('upload', $config);

        if (@$_FILES['gambarFile']['name'] != null) {
            if ($this->upload->do_upload('gambarFile')) {
                $post['gambarFile'] = $this->upload->data('file_name');
                $post['tipe_file'] = $this->upload->data('file_type');

                $this->resizeImage($post['gambarFile']);

                $params = [
                    'title' => $post['judul'],
                    'seo_title' => slugify($post['judul']),
                    'konten' => $post['konten'],
                    'gambar_name' => $post['gambarFile'],
                    'featured' => $post['featured'],
                    'thread' => $post['thread'],
                    'choice' => $post['choise'],
                    'user_id' => userdata('id_user'),
                    'kategori_id' => $post['kategori'],
                    'sub_kategori_id' => $post['sub_category'],
                    'author'  => $post['author'],
                    'isActive' => 1,
                    'viewers'   => 1,
                    'date' => date('Y-m-d')
                ];

                $this->base->edit('berita', $params, ['id_berita' => $post['id_berita']]);

                if ($this->db->affected_rows() > 0) {
                    set_pesan('Berita berhasil di tambahkan');
                } else {
                    set_pesan('Gagal menyimpan Berita, silahkan coba kembali', FALSE);
                }
            } else {
                echo 'error';
            }
        } else {
            $params = [
                'title' => $post['judul'],
                'seo_title' => slugify($post['judul']),
                'konten' => $post['konten'],
                'featured' => $post['featured'],
                'thread' => $post['thread'],
                'choice' => $post['choise'],
                'user_id' => userdata('id_user'),
                'kategori_id' => $post['kategori'],
                'author'  => $post['author'],
                'sub_kategori_id' => $post['sub_category'],
                'isActive' => 1,
                'viewers'   => 1,
            ];

            $this->base->edit('berita', $params, ['id_berita' => $post['id_berita']]);
        }

        redirect('posting');
    }

    public function delete($id)
    {
        $this->base->del('berita', ['id_berita' => $id]);

        if ($this->db->affected_rows() > 0) {
            set_pesan('Data berhasil dihapus');
        } else {
            set_pesan('Terjadi Kesalahan, Harap Coba Kembali', FALSE);
        }

        redirect('posting');
    }

    public function toggle($getId)
    {
        $status = $this->base_model->getUser('posting', ['id_posting' => $getId])['isActive'];

        // var_dump($status);
        $toggle = $status ? 0 : 1;
        $pesan = $toggle ? 'user diaktifkan.' : 'user dinonaktifkan.';

        if ($this->base_model->update('posting', 'id_posting', $getId, ['isActive' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('posting');
    }
}
