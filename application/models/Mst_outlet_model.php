<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_outlet_model extends CI_Model
{

    public $table = 'mst_outlet';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,code,name,address,bill_footer,status,outlet_type_id,cost_center_id,delivery_bill_footer,no_of_seats,m2,last_meal_period,curr_meal_period,list_number,num_of_employee,is_allow_cancel_tax,fo_gl_journal_code,bill_image_uri,bill_image_path,small_bill_image_uri,small_bill_image_path,printed_bill_image_uri,printed_bill_image_path,small_printed_bill_image_uri,small_printed_bill_image_path,created_date,modified_date,created_by,modified_by');
        $this->datatables->from('mst_outlet');
        //add this line for join
        //$this->datatables->join('table2', 'mst_outlet.field = table2.field');
        $this->datatables->add_column('action', anchor(base_url('master/mst_outlet/update/$1'),'Update')." | ".anchor(base_url('master/mst_outlet/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
	$this->db->or_like('address', $q);
	$this->db->or_like('bill_footer', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('outlet_type_id', $q);
	$this->db->or_like('cost_center_id', $q);
	$this->db->or_like('delivery_bill_footer', $q);
	$this->db->or_like('no_of_seats', $q);
	$this->db->or_like('m2', $q);
	$this->db->or_like('last_meal_period', $q);
	$this->db->or_like('curr_meal_period', $q);
	$this->db->or_like('list_number', $q);
	$this->db->or_like('num_of_employee', $q);
	$this->db->or_like('is_allow_cancel_tax', $q);
	$this->db->or_like('fo_gl_journal_code', $q);
	$this->db->or_like('bill_image_uri', $q);
	$this->db->or_like('bill_image_path', $q);
	$this->db->or_like('small_bill_image_uri', $q);
	$this->db->or_like('small_bill_image_path', $q);
	$this->db->or_like('printed_bill_image_uri', $q);
	$this->db->or_like('printed_bill_image_path', $q);
	$this->db->or_like('small_printed_bill_image_uri', $q);
	$this->db->or_like('small_printed_bill_image_path', $q);
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
	$this->db->or_like('address', $q);
	$this->db->or_like('bill_footer', $q);
	$this->db->or_like('status', $q);
	$this->db->or_like('outlet_type_id', $q);
	$this->db->or_like('cost_center_id', $q);
	$this->db->or_like('delivery_bill_footer', $q);
	$this->db->or_like('no_of_seats', $q);
	$this->db->or_like('m2', $q);
	$this->db->or_like('last_meal_period', $q);
	$this->db->or_like('curr_meal_period', $q);
	$this->db->or_like('list_number', $q);
	$this->db->or_like('num_of_employee', $q);
	$this->db->or_like('is_allow_cancel_tax', $q);
	$this->db->or_like('fo_gl_journal_code', $q);
	$this->db->or_like('bill_image_uri', $q);
	$this->db->or_like('bill_image_path', $q);
	$this->db->or_like('small_bill_image_uri', $q);
	$this->db->or_like('small_bill_image_path', $q);
	$this->db->or_like('printed_bill_image_uri', $q);
	$this->db->or_like('printed_bill_image_path', $q);
	$this->db->or_like('small_printed_bill_image_uri', $q);
	$this->db->or_like('small_printed_bill_image_path', $q);
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

/* End of file Mst_outlet_model.php */
/* Location: ./application/models/Mst_outlet_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-21 08:55:13 */
/* http://harviacode.com */