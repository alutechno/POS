<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ref_outlet_menu_class_model extends CI_Model {

	public $table = 'ref_outlet_menu_class';
	public $id = 'id';
	public $order = 'DESC';

	function __construct() {
		parent::__construct();
	}

	// datatables
	function json() {
		$this->datatables->select('id,code,name,short_name,description,status,parent_class_id,created_date,modified_date,created_by,modified_by');
		$this->datatables->from('ref_outlet_menu_class');
		//add this line for join
		//$this->datatables->join('table2', 'ref_outlet_menu_class.field = table2.field');
		$this->datatables->add_column('action', anchor(base_url('referensi/ref_outlet_menu_class/update/$1'), 'Update') . " | " . anchor(base_url('referensi/ref_outlet_menu_class/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
		$this->db->or_like('parent_class_id', $q);
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
		$this->db->or_like('parent_class_id', $q);
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

/* End of file Ref_outlet_menu_class_model.php */
/* Location: ./application/models/Ref_outlet_menu_class_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 19:20:49 */
/* http://harviacode.com */
