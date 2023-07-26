<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_model extends CI_Model
{
    public function get($where = null)
    {
        $this->db->select('*');
        $this->db->from('produks');
        $this->db->join('kategori', 'kategori.id_kategori = produks.kategori_id');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }
}
