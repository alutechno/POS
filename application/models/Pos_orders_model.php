<?php

	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Pos_orders_model extends CI_Model {

		public $table = 'pos_orders';
		public $id = 'id';
		public $order = 'DESC';

		function __construct() {
			parent::__construct();
		}

		// datatables
		function json() {
			$this->datatables->select('*');
			$this->datatables->from('pos_orders');
			$this->datatables->add_column('action', anchor(base_url('setting/pos_orders/update/$1'), 'Update') . " | " . anchor(base_url('setting/pos_orders/delete/$1'), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
			$this->db->or_like('num_of_cover', $q);
			$this->db->or_like('outlet_id', $q);
			$this->db->or_like('status', $q);
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

		// get data with limit and search
		function get_limit_data($limit, $start = 0, $q = NULL) {
			$this->db->order_by($this->id, $this->order);
			$this->db->like('id', $q);
			$this->db->or_like('num_of_cover', $q);
			$this->db->or_like('outlet_id', $q);
			$this->db->or_like('status', $q);
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
