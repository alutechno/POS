<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Inv_outlet_menus_model extends CI_Model {
		public $table = 'inv_outlet_menus';
		public $id = 'id';
		public $order = 'DESC';
		function __construct() {
			parent::__construct();
		}
		// datatables
		function json() {
			$this->datatables->select('inv_outlet_menus.id,image,inv_outlet_menus.code,inv_outlet_menus.name,inv_outlet_menus.short_name,inv_outlet_menus.description,mst_outlet.name as outlet_id,inv_outlet_menus.status');
			$this->datatables->from('inv_outlet_menus');
			$this->datatables->where("inv_outlet_menus.outlet_id=" . $this->session->userdata('outlet') . "");
			//add this line for join
			$this->datatables->join('mst_outlet', 'inv_outlet_menus.outlet_id = mst_outlet.id');
			$this->datatables->add_column('action',
										  anchor(base_url('referensi/inv_outlet_menus/update/$1'),
												 'Update') . " | " . anchor(base_url('referensi/inv_outlet_menus/delete/$1'),
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
			$this->db->or_like('outlet_id', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('menu_class_id', $q);
			$this->db->or_like('menu_group_id', $q);
			$this->db->or_like('menu_price', $q);
			$this->db->or_like('unit_cost', $q);
			$this->db->or_like('product_id', $q);
			$this->db->or_like('recipe_id', $q);
			$this->db->or_like('recipe_qty', $q);
			$this->db->or_like('is_promo_enabled', $q);
			$this->db->or_like('is_export_cost', $q);
			$this->db->or_like('is_print_after_total', $q);
			$this->db->or_like('is_disable_change_price', $q);
			$this->db->or_like('print_kitchen_id', $q);
			$this->db->or_like('print_kitchen_section_id', $q);
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
			$this->db->or_like('outlet_id', $q);
			$this->db->or_like('status', $q);
			$this->db->or_like('menu_class_id', $q);
			$this->db->or_like('menu_group_id', $q);
			$this->db->or_like('menu_price', $q);
			$this->db->or_like('unit_cost', $q);
			$this->db->or_like('product_id', $q);
			$this->db->or_like('recipe_id', $q);
			$this->db->or_like('recipe_qty', $q);
			$this->db->or_like('is_promo_enabled', $q);
			$this->db->or_like('is_export_cost', $q);
			$this->db->or_like('is_print_after_total', $q);
			$this->db->or_like('is_disable_change_price', $q);
			$this->db->or_like('print_kitchen_id', $q);
			$this->db->or_like('print_kitchen_section_id', $q);
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

		function getNextCode() {
			// $query = $this->db->call_function('next_item_code','outlet_menu');
			// $query = $this->db->query("select next_item_code('outlet_menu', #select code from mst_outlet#)");
			// echo "select next_item_code('outlet_menu', #select code from mst_outlet#)";
			// $query = $this->db->query("call next_item_code('outlet_menu', #select code from mst_outlet#)");
			// print_r($query);
			// foreach ($query->result() as $row) {
			// 	print_r($row);
			// }

			return 'OF'.rand(1,999);
		}
	}

	/* End of file Inv_outlet_menus_model.php */
	/* Location: ./application/models/Inv_outlet_menus_model.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 09:06:44 */
	/* http://harviacode.com */
