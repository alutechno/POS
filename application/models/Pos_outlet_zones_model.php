<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pos_outlet_zones_model extends CI_Model
{

    public $table = 'pos_outlet_zones';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('pos_outlet_zones.id,mst_outlet.name as store_id,pos_outlet_zones.name');
        $this->datatables->from('pos_outlet_zones');
        //add this line for join
        $this->datatables->join('mst_outlet', 'pos_outlet_zones.store_id = mst_outlet.id','left');
        
        $this->datatables->add_column('action', anchor(base_url('setting/pos_outlet_zones/update/$1'),'Update')." | ".anchor(base_url('setting/pos_outlet_zones/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
        return $this->datatables->generate();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('store_id', $q);
	$this->db->or_like('name', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('store_id', $q);
	$this->db->or_like('name', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Pos_outlet_zones_model.php */
/* Location: ./application/models/Pos_outlet_zones_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-06 17:23:32 */
/* http://harviacode.com */