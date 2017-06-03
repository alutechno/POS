<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Ref_payment_method_model extends CI_Model {
		public $table = 'ref_payment_method';
		public $id = 'id';
		public $order = 'DESC';
		function __construct() {
			parent::__construct();
		}
		// datatables
		function json() {
			$this->datatables->select('id,code,name,description, status,credit_limit,is_credit_card,account_id');
			$this->datatables->from('ref_payment_method');
			$this->datatables->where('is_pos_enabled', 'Y');
			//add this line for join
			//$this->datatables->join('table2', 'ref_payment_method.field = table2.field');
			$this->datatables->add_column('action',
										  anchor(base_url('referensi/ref_payment_method/update/$1'),
												 'Update') . " | " . anchor(base_url('referensi/ref_payment_method/delete/$1'),
																			'Delete',
																			'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'),
										  'id');

			return $this->datatables->generate();
		}
		// get all
		function get_all() {
			$this->db->order_by($this->id, $this->order);
			$this->db->where('is_pos_enabled', 'Y');

			return $this->db->get($this->table)->result();
		}
		// get data by id
		function get_by_id($id) {
			$this->db->where($this->id, $id);
			$this->db->where('is_pos_enabled', 'Y');

			return $this->db->get($this->table)->row();
		}
		// get total rows
		function total_rows($q = null) {
			$this->db->where('is_pos_enabled', 'Y');
			$this->db->like('id', $q);
			$this->db->or_like('code', $q);
			$this->db->or_like('name', $q);
			$this->db->or_like('description', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('credit_limit', $q);
			$this->db->or_like('is_credit_card', $q);
			$this->db->or_like('account_id', $q);
			$this->db->or_like('created_date', $q);
			$this->db->or_like('modified_date', $q);
			$this->db->or_like('created_by', $q);
			$this->db->or_like('modified_by', $q);
			$this->db->from($this->table);

			return $this->db->count_all_results();
		}
		// get data with limit and search
		function get_limit_data($limit, $start = 0, $q = null) {
			$this->db->where('is_pos_enabled', 'Y');
			$this->db->order_by($this->id, $this->order);
			$this->db->like('id', $q);
			$this->db->or_like('code', $q);
			$this->db->or_like('name', $q);
			$this->db->or_like('description', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('credit_limit', $q);
			$this->db->or_like('is_credit_card', $q);
			$this->db->or_like('account_id', $q);
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
			$this->db->where('is_pos_enabled', 'Y');
			$this->db->update($this->table, $data);
		}
		// delete data
		function delete($id) {
			//$this->db->where($this->id, $id);
			// $this->db->delete($this->table);
			$this->db->set('status', 2);
			$this->db->where($this->id, $id);
			$this->db->where('is_pos_enabled', 'Y');
			$this->db->update($this->table);
		}
	}

	/* End of file Ref_payment_method_model.php */
	/* Location: ./application/models/Ref_payment_method_model.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 17:07:53 */
	/* http://harviacode.com */
