<?php

	/**
	 * Created by IntelliJ IDEA.
	 * User: rappresent
	 * Date: 6/3/17
	 * Time: 10:20 PM
	 */
	class Pos_orders_line_item_model {

		public $table = 'pos_orders_line_item';
		public $id = 'id';
		public $order = 'DESC';

		function __construct() {
			parent::__construct();
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
			$this->db->or_like('order_id', $q);
			$this->db->or_like('menu_class_id', $q);
			$this->db->or_like('outlet_menu_id', $q);
			$this->db->or_like('serving_status', $q);
			$this->db->or_like('order_qty', $q);
			$this->db->or_like('price_amount', $q);
			$this->db->or_like('total_amount', $q);
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

		// get data with limit and search
		function get_limit_data($limit, $start = 0, $q = NULL) {
			$this->db->order_by($this->id, $this->order);
			$this->db->like('id', $q);
			$this->db->or_like('order_id', $q);
			$this->db->or_like('menu_class_id', $q);
			$this->db->or_like('outlet_menu_id', $q);
			$this->db->or_like('serving_status', $q);
			$this->db->or_like('order_qty', $q);
			$this->db->or_like('price_amount', $q);
			$this->db->or_like('total_amount', $q);
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
