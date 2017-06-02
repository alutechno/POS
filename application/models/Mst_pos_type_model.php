<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Mst_pos_type_model extends CI_Model {
		public $table = 'mst_pos_type';
		public $id = 'id';
		public $order = 'DESC';
		function __construct() {
			parent::__construct();
		}
		// datatables
		function json() {
			$this->datatables->select('id,code,name,short_name,description,status,outlet_id,created_date,modified_date,created_by,modified_by');
			$this->datatables->from('mst_pos_type');
			//add this line for join
			//$this->datatables->join('table2', 'mst_pos_type.field = table2.field');
			$this->datatables->add_column('action', anchor(site_url('mst_pos_type/read/$1'),
														   'Read') . " | " . anchor(site_url('mst_pos_type/update/$1'),
																					'Update') . " | " . anchor(site_url('mst_pos_type/delete/$1'),
																											   'Delete',
																											   'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'),
										  'id');

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
		function total_rows($q = null) {
			$this->db->like('id', $q);
			$this->db->or_like('code', $q);
			$this->db->or_like('name', $q);
			$this->db->or_like('short_name', $q);
			$this->db->or_like('description', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('outlet_id', $q);
			$this->db->or_like('created_date', $q);
			$this->db->or_like('modified_date', $q);
			$this->db->or_like('created_by', $q);
			$this->db->or_like('modified_by', $q);
			$this->db->from($this->table);

			return $this->db->count_all_results();
		}
		// get data with limit and search
		function get_limit_data($limit, $start = 0, $q = null) {
			$this->db->order_by($this->id, $this->order);
			$this->db->like('id', $q);
			$this->db->or_like('code', $q);
			$this->db->or_like('name', $q);
			$this->db->or_like('short_name', $q);
			$this->db->or_like('description', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('outlet_id', $q);
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

	/* End of file Mst_pos_type_model.php */
	/* Location: ./application/models/Mst_pos_type_model.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 19:26:49 */
	/* http://harviacode.com */
