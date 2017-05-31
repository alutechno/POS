<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_cuisine_recipe extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mst_cuisine_recipe_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
              is_logged_in();
    }

    public function index()
    {
        $data['title']='Cuisine Recipe';
        datatables('master/mst_cuisine_recipe/mst_cuisine_recipe_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Mst_cuisine_recipe_model->json();
    }

    public function read($id) 
    {
        $row = $this->Mst_cuisine_recipe_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
		'name' => $row->name,
		'short_name' => $row->short_name,
		'description' => $row->description,
		'cooking_direction' => $row->cooking_direction,
		'picture_link' => $row->picture_link,
		'status' => $row->status,
		'cost_center_id' => $row->cost_center_id,
		'meal_time_id' => $row->meal_time_id,
		'reg_style_id' => $row->reg_style_id,
		'category_id' => $row->category_id,
		'producing_qty' => $row->producing_qty,
		'unit_type_id' => $row->unit_type_id,
		'level' => $row->level,
		'product_cost' => $row->product_cost,
		'product_price' => $row->product_price,
		'makeup_cost_percent' => $row->makeup_cost_percent,
		'expected_cost_percent' => $row->expected_cost_percent,
		'selling_price' => $row->selling_price,
		'last_unit_cost' => $row->last_unit_cost,
		'last_cost_percent' => $row->last_cost_percent,
		'last_suggestion_price' => $row->last_suggestion_price,
		'onhand_unit_cost' => $row->onhand_unit_cost,
		'onhand_cost_percent' => $row->onhand_cost_percent,
		'onhand_suggestion_price' => $row->onhand_suggestion_price,
		'manual_unit_cost' => $row->manual_unit_cost,
		'manual_cost_percent' => $row->manual_cost_percent,
		'manual_suggestion_price' => $row->manual_suggestion_price,
		
	    );
            $this->load->view('master/mst_cuisine_recipe/mst_cuisine_recipe_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('mst_cuisine_recipe'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'title'=>'Cuisine Recipe',
            'action' => base_url('master/mst_cuisine_recipe/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'short_name' => set_value('short_name'),
	    'description' => set_value('description'),
	    'cooking_direction' => set_value('cooking_direction'),
	    'picture_link' => set_value('picture_link'),
	    'status' => set_value('status'),
	    'cost_center_id' => set_value('cost_center_id'),
	    'meal_time_id' => set_value('meal_time_id'),
	    'reg_style_id' => set_value('reg_style_id'),
	    'category_id' => set_value('category_id'),
	    'producing_qty' => set_value('producing_qty'),
	    'unit_type_id' => set_value('unit_type_id'),
	    'level' => set_value('level'),
	    'product_cost' => set_value('product_cost'),
	    'product_price' => set_value('product_price'),
	    'makeup_cost_percent' => set_value('makeup_cost_percent'),
	    'expected_cost_percent' => set_value('expected_cost_percent'),
	    'selling_price' => set_value('selling_price'),
	    'last_unit_cost' => set_value('last_unit_cost'),
	    'last_cost_percent' => set_value('last_cost_percent'),
	    'last_suggestion_price' => set_value('last_suggestion_price'),
	    'onhand_unit_cost' => set_value('onhand_unit_cost'),
	    'onhand_cost_percent' => set_value('onhand_cost_percent'),
	    'onhand_suggestion_price' => set_value('onhand_suggestion_price'),
	    'manual_unit_cost' => set_value('manual_unit_cost'),
	    'manual_cost_percent' => set_value('manual_cost_percent'),
	    'manual_suggestion_price' => set_value('manual_suggestion_price'),
	    
	);
        form('master/mst_cuisine_recipe/mst_cuisine_recipe_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'code' => $this->input->post('code',TRUE),
		'name' => $this->input->post('name',TRUE),
		'short_name' => $this->input->post('short_name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'cooking_direction' => $this->input->post('cooking_direction',TRUE),
		'picture_link' => $this->input->post('picture_link',TRUE),
		'status' => $this->input->post('status',TRUE),
		'cost_center_id' => $this->input->post('cost_center_id',TRUE),
		'meal_time_id' => $this->input->post('meal_time_id',TRUE),
		'reg_style_id' => $this->input->post('reg_style_id',TRUE),
		'category_id' => $this->input->post('category_id',TRUE),
		'producing_qty' => $this->input->post('producing_qty',TRUE),
		'unit_type_id' => $this->input->post('unit_type_id',TRUE),
		'level' => $this->input->post('level',TRUE),
		'product_cost' => $this->input->post('product_cost',TRUE),
		'product_price' => $this->input->post('product_price',TRUE),
		'makeup_cost_percent' => $this->input->post('makeup_cost_percent',TRUE),
		'expected_cost_percent' => $this->input->post('expected_cost_percent',TRUE),
		'selling_price' => $this->input->post('selling_price',TRUE),
		'last_unit_cost' => $this->input->post('last_unit_cost',TRUE),
		'last_cost_percent' => $this->input->post('last_cost_percent',TRUE),
		'last_suggestion_price' => $this->input->post('last_suggestion_price',TRUE),
		'onhand_unit_cost' => $this->input->post('onhand_unit_cost',TRUE),
		'onhand_cost_percent' => $this->input->post('onhand_cost_percent',TRUE),
		'onhand_suggestion_price' => $this->input->post('onhand_suggestion_price',TRUE),
		'manual_unit_cost' => $this->input->post('manual_unit_cost',TRUE),
		'manual_cost_percent' => $this->input->post('manual_cost_percent',TRUE),
		'manual_suggestion_price' => $this->input->post('manual_suggestion_price',TRUE),
	    );

            $this->Mst_cuisine_recipe_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('master/mst_cuisine_recipe'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mst_cuisine_recipe_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Cuisine Recipe',
                'action' => base_url('master/mst_cuisine_recipe/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'short_name' => set_value('short_name', $row->short_name),
		'description' => set_value('description', $row->description),
		'cooking_direction' => set_value('cooking_direction', $row->cooking_direction),
		'picture_link' => set_value('picture_link', $row->picture_link),
		'status' => set_value('status', $row->status),
		'cost_center_id' => set_value('cost_center_id', $row->cost_center_id),
		'meal_time_id' => set_value('meal_time_id', $row->meal_time_id),
		'reg_style_id' => set_value('reg_style_id', $row->reg_style_id),
		'category_id' => set_value('category_id', $row->category_id),
		'producing_qty' => set_value('producing_qty', $row->producing_qty),
		'unit_type_id' => set_value('unit_type_id', $row->unit_type_id),
		'level' => set_value('level', $row->level),
		'product_cost' => set_value('product_cost', $row->product_cost),
		'product_price' => set_value('product_price', $row->product_price),
		'makeup_cost_percent' => set_value('makeup_cost_percent', $row->makeup_cost_percent),
		'expected_cost_percent' => set_value('expected_cost_percent', $row->expected_cost_percent),
		'selling_price' => set_value('selling_price', $row->selling_price),
		'last_unit_cost' => set_value('last_unit_cost', $row->last_unit_cost),
		'last_cost_percent' => set_value('last_cost_percent', $row->last_cost_percent),
		'last_suggestion_price' => set_value('last_suggestion_price', $row->last_suggestion_price),
		'onhand_unit_cost' => set_value('onhand_unit_cost', $row->onhand_unit_cost),
		'onhand_cost_percent' => set_value('onhand_cost_percent', $row->onhand_cost_percent),
		'onhand_suggestion_price' => set_value('onhand_suggestion_price', $row->onhand_suggestion_price),
		'manual_unit_cost' => set_value('manual_unit_cost', $row->manual_unit_cost),
		'manual_cost_percent' => set_value('manual_cost_percent', $row->manual_cost_percent),
		'manual_suggestion_price' => set_value('manual_suggestion_price', $row->manual_suggestion_price),
	    );
           form('master/mst_cuisine_recipe/mst_cuisine_recipe_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_cuisine_recipe'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'code' => $this->input->post('code',TRUE),
		'name' => $this->input->post('name',TRUE),
		'short_name' => $this->input->post('short_name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'cooking_direction' => $this->input->post('cooking_direction',TRUE),
		'picture_link' => $this->input->post('picture_link',TRUE),
		'status' => $this->input->post('status',TRUE),
		'cost_center_id' => $this->input->post('cost_center_id',TRUE),
		'meal_time_id' => $this->input->post('meal_time_id',TRUE),
		'reg_style_id' => $this->input->post('reg_style_id',TRUE),
		'category_id' => $this->input->post('category_id',TRUE),
		'producing_qty' => $this->input->post('producing_qty',TRUE),
		'unit_type_id' => $this->input->post('unit_type_id',TRUE),
		'level' => $this->input->post('level',TRUE),
		'product_cost' => $this->input->post('product_cost',TRUE),
		'product_price' => $this->input->post('product_price',TRUE),
		'makeup_cost_percent' => $this->input->post('makeup_cost_percent',TRUE),
		'expected_cost_percent' => $this->input->post('expected_cost_percent',TRUE),
		'selling_price' => $this->input->post('selling_price',TRUE),
		'last_unit_cost' => $this->input->post('last_unit_cost',TRUE),
		'last_cost_percent' => $this->input->post('last_cost_percent',TRUE),
		'last_suggestion_price' => $this->input->post('last_suggestion_price',TRUE),
		'onhand_unit_cost' => $this->input->post('onhand_unit_cost',TRUE),
		'onhand_cost_percent' => $this->input->post('onhand_cost_percent',TRUE),
		'onhand_suggestion_price' => $this->input->post('onhand_suggestion_price',TRUE),
		'manual_unit_cost' => $this->input->post('manual_unit_cost',TRUE),
		'manual_cost_percent' => $this->input->post('manual_cost_percent',TRUE),
		'manual_suggestion_price' => $this->input->post('manual_suggestion_price',TRUE),
		
	    );

            $this->Mst_cuisine_recipe_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('master/mst_cuisine_recipe'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mst_cuisine_recipe_model->get_by_id($id);

        if ($row) {
            $this->Mst_cuisine_recipe_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('master/mst_cuisine_recipe'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_cuisine_recipe'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('cooking_direction', 'cooking direction', 'trim|required');
	$this->form_validation->set_rules('picture_link', 'picture link', 'trim|required');
	/*$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('cost_center_id', 'cost center id', 'trim|required');
	$this->form_validation->set_rules('meal_time_id', 'meal time id', 'trim|required');
	$this->form_validation->set_rules('reg_style_id', 'reg style id', 'trim|required');
	$this->form_validation->set_rules('category_id', 'category id', 'trim|required');
	$this->form_validation->set_rules('producing_qty', 'producing qty', 'trim|required|numeric');
	$this->form_validation->set_rules('unit_type_id', 'unit type id', 'trim|required');
	$this->form_validation->set_rules('level', 'level', 'trim|required');
	$this->form_validation->set_rules('product_cost', 'product cost', 'trim|required|numeric');
	$this->form_validation->set_rules('product_price', 'product price', 'trim|required|numeric');
	$this->form_validation->set_rules('makeup_cost_percent', 'makeup cost percent', 'trim|required|numeric');
	$this->form_validation->set_rules('expected_cost_percent', 'expected cost percent', 'trim|required|numeric');
	$this->form_validation->set_rules('selling_price', 'selling price', 'trim|required|numeric');
	$this->form_validation->set_rules('last_unit_cost', 'last unit cost', 'trim|required|numeric');
	$this->form_validation->set_rules('last_cost_percent', 'last cost percent', 'trim|required|numeric');
	$this->form_validation->set_rules('last_suggestion_price', 'last suggestion price', 'trim|required|numeric');
	$this->form_validation->set_rules('onhand_unit_cost', 'onhand unit cost', 'trim|required|numeric');
	$this->form_validation->set_rules('onhand_cost_percent', 'onhand cost percent', 'trim|required|numeric');
	$this->form_validation->set_rules('onhand_suggestion_price', 'onhand suggestion price', 'trim|required|numeric');
	$this->form_validation->set_rules('manual_unit_cost', 'manual unit cost', 'trim|required|numeric');
	$this->form_validation->set_rules('manual_cost_percent', 'manual cost percent', 'trim|required|numeric');
	$this->form_validation->set_rules('manual_suggestion_price', 'manual suggestion price', 'trim|required|numeric');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');*/

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Mst_cuisine_recipe.php */
/* Location: ./application/controllers/Mst_cuisine_recipe.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-21 08:40:35 */
/* http://harviacode.com */