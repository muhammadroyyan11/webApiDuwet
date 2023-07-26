<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_model extends CI_Model
{

    public function getUser($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function getUsers($id)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }

    public function get($table, $where = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $sql = $this->db->get($table);
        return $sql;
    }

    public function getDetail($where = null)
    {
        $this->db->select('*');
        $this->db->from('transaksi_details as a');
        $this->db->join('transaksis as b', 'a.transaksi_id=b.id');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }
    


    public function getTable($table, $where = null)
    {
        $this->db->select('*');
        $this->db->from($table);
        if ($where != null) {
            $this->db->where($where);
        }

        return $this->db->get();
    }

    public function getBerita($where = null)
    {
        $this->db->select('*');
        $this->db->from('berita');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->join('kategori', 'kategori.id_kategori = berita.kategori_id');

        $this->db->order_by('id_berita', 'desc');

        return $this->db->get();
    }

    public function count($table)
    {
        return $this->db->get($table)->num_rows();
    }

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null) {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function get_max_id($table, $field, $where)
    {
        $this->db->select_max($field);
        $this->db->where($where);
        $sql = $this->db->get($table);
        return $sql;
    }
    public function get_group_id($table, $group_by)
    {
        $this->db->group_by($group_by);
        $this->db->order_by($group_by . " DESC");
        $sql = $this->db->get($table);
        return $sql;
    }
    public function add($table, $data)
    {
        $this->db->insert($table, $data);
    }
    public function del($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }
    public function edit($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function getSubKategori($where = null)
    {
        $this->db->select('*');
        $this->db->from('sub_kategori');
        $this->db->join('kategori', 'kategori.id_kategori = sub_kategori.kategori_id');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }

    public function join_multiple($table, $join, $pq, $join1, $pq1, $order, $az)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->join($join, $pq);
        $this->db->join($join1, $pq1);
        $this->db->order_by($order, $az);
        $sql = $this->db->get();
        return $sql;
    }
    public function get_id($table, $where)
    {
        $this->db->where($where);
        $sql = $this->db->get($table);
        return $sql;
    }
    public function fetch_data($table, $field, $num, $offset)
    {
        empty($offset) ? $offset = 0 : $offset;

        $this->db->query("SET @no=" . $offset);
        $this->db->select('*,(@no:=@no+1) AS nomor');
        $this->db->group_by($field);
        $this->db->order_by($field, 'DESC');

        $data = $this->db->get($table, $num, $offset);

        return $data->result();
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getDefaultValues()
    {
        return [
            'judul'        => '',
            'seo_judul'    => '',
            'konten'      => '',
            'description'      => '',
            'featured'     => 'N',
            'choice'       => 'N',
            'thread'       => 'N',
            'id_category'  => '',
            'photo'        => '',
            'is_active'    => '1',
            'date'         => ''
        ];
    }
}
