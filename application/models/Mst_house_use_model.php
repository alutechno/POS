<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mst_house_use_model extends CI_Model {

	public $table = 'mst_house_use';
	public $id = 'id';
	public $order = 'DESC';

	function __construct() {
		parent::__construct();
	}

	// datatables
	function json() {
		$this->datatables->select('id,code,name,short_name,description,status,pos_cost_center_id,max_spent_monthly,created_date,modified_date,created_by,modified_by');
		$this->datatables->from('mst_house_use');
		//add this line for join
		//$this->datatables->join('table2', 'mst_house_use.field = table2.field');
		$this->datatables->add_column('action', anchor(site_url('mst_house_use/read/$1'), 'Read') . " | " . anchor(site_url('mst_house_use/update/$1'), 'Update') . " | " . anchor(site_url('mst_house_use/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
		return $this->datatables->generate();
	}

	// get all
	function get_all() {
		$this->db->order_by($this->id, $this->order);
		return $this->db->get($this->table)->result();
	}

	// get data by id
	function get_by_id($id) {
		$this->db->where($this->id, $id);
		return $this->db->get($this->table)->row();
	}

	// get total rows
	function total_rows($q = NULL) {
		$this->db->like('id', $q);
		$this->db->or_like('code', $q);
		$this->db->or_like('name', $q);
		$this->db->or_like('short_name', $q);
		$this->db->or_like('description', $q);
		$this->db->or_like('status', $q);
		$this->db->or_like('pos_cost_center_id', $q);
		$this->db->or_like('max_spent_monthly', $q);
		$this->db->or_like('created_date', $q);
		$this->db->or_like('modified_date', $q);
		$this->db->or_like('created_by', $q);
		$this->db->or_like('modified_by', $q);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	// get data with limit and search
	function get_limit_data($limit, $start = 0, $q = NULL) {
		$this->db->order_by($this->id, $this->order);
		$this->db->like('id', $q);
		$this->db->or_like('code', $q);
		$this->db->or_like('name', $q);
		$this->db->or_like('short_name', $q);
		$this->db->or_like('description', $q);
		$this->db->or_like('status', $q);
		$this->db->or_like('pos_cost_center_id', $q);
		$this->db->or_like('max_spent_monthly', $q);
		$this->db->or_like('created_date', $q);
		$this->db->or_like('modified_date', $q);
		$this->db->or_like('created_by', $q);
		$this->db->or_like('modified_by', $q);
		$this->db->limit($limit, $start);
		return $this->db->get($this->table)->result();
	}

	// insert data
	function insert($data) {
		$this->db->insert($this->table, $data);
	}

	// update data
	function update($id, $data) {
		$this->db->where($this->id, $id);
		$this->db->update($this->table, $data);
	}

	// delete data
	function delete($id) {
		$this->db->where($this->id, $id);
		$this->db->delete($this->table);
	}

}

/* End of file Mst_house_use_model.php */
/* Location: ./application/models/Mst_house_use_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 18:59:00 */
/* http://harviacode.com */
