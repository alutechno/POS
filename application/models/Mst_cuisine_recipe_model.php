<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_cuisine_recipe_model extends CI_Model
{

    public $table = 'mst_cuisine_recipe';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,code,name,short_name,description,cooking_direction,picture_link,status,cost_center_id,meal_time_id,reg_style_id,category_id,producing_qty,unit_type_id,level,product_cost,product_price,makeup_cost_percent,expected_cost_percent,selling_price,last_unit_cost,last_cost_percent,last_suggestion_price,onhand_unit_cost,onhand_cost_percent,onhand_suggestion_price,manual_unit_cost,manual_cost_percent,manual_suggestion_price,created_date,modified_date,created_by,modified_by');
        $this->datatables->from('mst_cuisine_recipe');
        //add this line for join
        //$this->datatables->join('table2', 'mst_cuisine_recipe.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('mst_cuisine_recipe/read/$1'),'Read')." | ".anchor(site_url('mst_cuisine_recipe/update/$1'),'Update')." | ".anchor(site_url('mst_cuisine_recipe/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
	$this->db->or_like('code', $q);
	$this->db->or_like('name', $q);
	$this->db->or_like('short_name', $q);
	$this->db->or_like('description', $q);
	$this->db->or_like('cooking_direction', $q);
	$this->db->or_like('picture_link', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('cost_center_id', $q);
	$this->db->or_like('meal_time_id', $q);
	$this->db->or_like('reg_style_id', $q);
	$this->db->or_like('category_id', $q);
	$this->db->or_like('producing_qty', $q);
	$this->db->or_like('unit_type_id', $q);
	$this->db->or_like('level', $q);
	$this->db->or_like('product_cost', $q);
	$this->db->or_like('product_price', $q);
	$this->db->or_like('makeup_cost_percent', $q);
	$this->db->or_like('expected_cost_percent', $q);
	$this->db->or_like('selling_price', $q);
	$this->db->or_like('last_unit_cost', $q);
	$this->db->or_like('last_cost_percent', $q);
	$this->db->or_like('last_suggestion_price', $q);
	$this->db->or_like('onhand_unit_cost', $q);
	$this->db->or_like('onhand_cost_percent', $q);
	$this->db->or_like('onhand_suggestion_price', $q);
	$this->db->or_like('manual_unit_cost', $q);
	$this->db->or_like('manual_cost_percent', $q);
	$this->db->or_like('manual_suggestion_price', $q);
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
	$this->db->or_like('cooking_direction', $q);
	$this->db->or_like('picture_link', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('cost_center_id', $q);
	$this->db->or_like('meal_time_id', $q);
	$this->db->or_like('reg_style_id', $q);
	$this->db->or_like('category_id', $q);
	$this->db->or_like('producing_qty', $q);
	$this->db->or_like('unit_type_id', $q);
	$this->db->or_like('level', $q);
	$this->db->or_like('product_cost', $q);
	$this->db->or_like('product_price', $q);
	$this->db->or_like('makeup_cost_percent', $q);
	$this->db->or_like('expected_cost_percent', $q);
	$this->db->or_like('selling_price', $q);
	$this->db->or_like('last_unit_cost', $q);
	$this->db->or_like('last_cost_percent', $q);
	$this->db->or_like('last_suggestion_price', $q);
	$this->db->or_like('onhand_unit_cost', $q);
	$this->db->or_like('onhand_cost_percent', $q);
	$this->db->or_like('onhand_suggestion_price', $q);
	$this->db->or_like('manual_unit_cost', $q);
	$this->db->or_like('manual_cost_percent', $q);
	$this->db->or_like('manual_suggestion_price', $q);
	$this->db->or_like('created_date', $q);
	$this->db->or_like('modified_date', $q);
	$this->db->or_like('created_by', $q);
	$this->db->or_like('modified_by', $q);
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

/* End of file Mst_cuisine_recipe_model.php */
/* Location: ./application/models/Mst_cuisine_recipe_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-21 08:40:35 */
/* http://harviacode.com */