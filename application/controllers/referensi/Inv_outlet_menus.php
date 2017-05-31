<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inv_outlet_menus extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Inv_outlet_menus_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
         is_logged_in();
    }

    
    public function index()
    {
        $data['title']="Menu List";
        datatables('referensi/inv_outlet_menus/inv_outlet_menus_list',$data);
    }
    
        
    public function json() {
        header('Content-Type: application/json');
        echo $this->Inv_outlet_menus_model->json();
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'title'=>'Menu List',
            'action' => base_url('referensi/inv_outlet_menus/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'short_name' => set_value('short_name'),
	    'description' => set_value('description'),
	    'outlet_id' => set_value('outlet_id'),
	    'status' => set_value('status'),
	    'menu_class_id' => set_value('menu_class_id'),
	    'menu_group_id' => set_value('menu_group_id'),
	    'menu_price' => set_value('menu_price'),
	    'unit_cost' => set_value('unit_cost'),
	      'image' => set_value('image'),
	    'product_id' => set_value('product_id'),
	    'recipe_id' => set_value('recipe_id'),
	    'recipe_qty' => set_value('recipe_qty'),
	    'is_promo_enabled' => set_value('is_promo_enabled'),
	    'is_export_cost' => set_value('is_export_cost'),
	    'is_print_after_total' => set_value('is_print_after_total'),
	    'is_disable_change_price' => set_value('is_disable_change_price'),
	    'print_kitchen_id' => set_value('print_kitchen_id'),
	    'print_kitchen_section_id' => set_value('print_kitchen_section_id'),
	);
        form('referensi/inv_outlet_menus/inv_outlet_menus_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
   
         if($_FILES['file']['name'] <> "")
         {
                        $ext1 = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$unique_id1 = md5($_FILES['file']['name']);
			$file_name=$unique_id1.".".$ext1;
                   
                        move_uploaded_file($_FILES['file']['tmp_name'], './menu/'.$file_name);
                        
                    $data = array(
                        'code' => $this->input->post('code',TRUE),
                        'name' => $this->input->post('name',TRUE),
                        'image'=>$file_name,
                        'short_name' => $this->input->post('short_name',TRUE),
                        'description' => $this->input->post('description',TRUE),
                        'outlet_id' => $this->input->post('outlet_id',TRUE),
                        'status' => $this->input->post('status',TRUE),
                        'menu_class_id' => $this->input->post('menu_class_id',TRUE),
                        'menu_group_id' => $this->input->post('menu_group_id',TRUE),
                        'menu_price' => $this->input->post('menu_price',TRUE),
                        'unit_cost' => $this->input->post('unit_cost',TRUE),
                        'product_id' => $this->input->post('product_id',TRUE),
                        'recipe_id' => $this->input->post('recipe_id',TRUE),
                        'recipe_qty' => $this->input->post('recipe_qty',TRUE),
                        'is_promo_enabled' => $this->input->post('is_promo_enabled',TRUE),
                        'is_export_cost' => $this->input->post('is_export_cost',TRUE),
                        'is_print_after_total' => $this->input->post('is_print_after_total',TRUE),
                        'is_disable_change_price' => $this->input->post('is_disable_change_price',TRUE),
                        'print_kitchen_id' => $this->input->post('print_kitchen_id',TRUE),
                        'print_kitchen_section_id' => $this->input->post('print_kitchen_section_id',TRUE),
                    );
         }else{
             
                 
            $data = array(
		'code' => $this->input->post('code',TRUE),
		'name' => $this->input->post('name',TRUE),
		'short_name' => $this->input->post('short_name',TRUE),
                'description' => $this->input->post('description',TRUE),
		'outlet_id' => $this->input->post('outlet_id',TRUE),
		'status' => $this->input->post('status',TRUE),
		'menu_class_id' => $this->input->post('menu_class_id',TRUE),
		'menu_group_id' => $this->input->post('menu_group_id',TRUE),
		'menu_price' => $this->input->post('menu_price',TRUE),
		'unit_cost' => $this->input->post('unit_cost',TRUE),
		'product_id' => $this->input->post('product_id',TRUE),
		'recipe_id' => $this->input->post('recipe_id',TRUE),
		'recipe_qty' => $this->input->post('recipe_qty',TRUE),
		'is_promo_enabled' => $this->input->post('is_promo_enabled',TRUE),
		'is_export_cost' => $this->input->post('is_export_cost',TRUE),
		'is_print_after_total' => $this->input->post('is_print_after_total',TRUE),
		'is_disable_change_price' => $this->input->post('is_disable_change_price',TRUE),
		'print_kitchen_id' => $this->input->post('print_kitchen_id',TRUE),
		'print_kitchen_section_id' => $this->input->post('print_kitchen_section_id',TRUE),
	    );
         }
            $this->Inv_outlet_menus_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('referensi/inv_outlet_menus'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Inv_outlet_menus_model->get_by_id($id);

        if ($row) {
           // echo $row->image;exit;
            $data = array(
                'button' => 'Update',
                'title'=>'Menu List',
                'action' => base_url('referensi/inv_outlet_menus/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
                'image'=> set_value('image', $row->image),
		'short_name' => set_value('short_name', $row->short_name),
		'description' => set_value('description', $row->description),
		'outlet_id' => set_value('outlet_id', $row->outlet_id),
		'status' => set_value('status', $row->status),
		'menu_class_id' => set_value('menu_class_id', $row->menu_class_id),
		'menu_group_id' => set_value('menu_group_id', $row->menu_group_id),
		'menu_price' => set_value('menu_price', $row->menu_price),
		'unit_cost' => set_value('unit_cost', $row->unit_cost),
		'product_id' => set_value('product_id', $row->product_id),
		'recipe_id' => set_value('recipe_id', $row->recipe_id),
		'recipe_qty' => set_value('recipe_qty', $row->recipe_qty),
		'is_promo_enabled' => set_value('is_promo_enabled', $row->is_promo_enabled),
		'is_export_cost' => set_value('is_export_cost', $row->is_export_cost),
		'is_print_after_total' => set_value('is_print_after_total', $row->is_print_after_total),
		'is_disable_change_price' => set_value('is_disable_change_price', $row->is_disable_change_price),
		'print_kitchen_id' => set_value('print_kitchen_id', $row->print_kitchen_id),
		'print_kitchen_section_id' => set_value('print_kitchen_section_id', $row->print_kitchen_section_id),
	    );
            form('referensi/inv_outlet_menus/inv_outlet_menus_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/inv_outlet_menus'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            //echo "ssss";exit;
            //echo $this->input->post('id', TRUE);exit;
            if($_FILES['file']['name'] <> "")
         {
                 $ext1 = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                 $unique_id1 = md5($_FILES['file']['name']);
	         $file_name=$unique_id1.".".$ext1;
                   
                 move_uploaded_file($_FILES['file']['tmp_name'], './menu/'.$file_name);
                       
            $data = array(
		'code' => $this->input->post('code',TRUE),
		'name' => $this->input->post('name',TRUE),
                'image'=>$file_name,
		'short_name' => $this->input->post('short_name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'outlet_id' => $this->input->post('outlet_id',TRUE),
		'status' => $this->input->post('status',TRUE),
		'menu_class_id' => $this->input->post('menu_class_id',TRUE),
		'menu_group_id' => $this->input->post('menu_group_id',TRUE),
		'menu_price' => $this->input->post('menu_price',TRUE),
		'unit_cost' => $this->input->post('unit_cost',TRUE),
		'product_id' => $this->input->post('product_id',TRUE),
		'recipe_id' => $this->input->post('recipe_id',TRUE),
		'recipe_qty' => $this->input->post('recipe_qty',TRUE),
		'is_promo_enabled' => $this->input->post('is_promo_enabled',TRUE),
		'is_export_cost' => $this->input->post('is_export_cost',TRUE),
		'is_print_after_total' => $this->input->post('is_print_after_total',TRUE),
		'is_disable_change_price' => $this->input->post('is_disable_change_price',TRUE),
		'print_kitchen_id' => $this->input->post('print_kitchen_id',TRUE),
		'print_kitchen_section_id' => $this->input->post('print_kitchen_section_id',TRUE),
	    );
         }else{
             $data = array(
		'code' => $this->input->post('code',TRUE),
		'name' => $this->input->post('name',TRUE),
		'short_name' => $this->input->post('short_name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'outlet_id' => $this->input->post('outlet_id',TRUE),
		'status' => $this->input->post('status',TRUE),
		'menu_class_id' => $this->input->post('menu_class_id',TRUE),
		'menu_group_id' => $this->input->post('menu_group_id',TRUE),
		'menu_price' => $this->input->post('menu_price',TRUE),
		'unit_cost' => $this->input->post('unit_cost',TRUE),
		'product_id' => $this->input->post('product_id',TRUE),
		'recipe_id' => $this->input->post('recipe_id',TRUE),
		'recipe_qty' => $this->input->post('recipe_qty',TRUE),
		'is_promo_enabled' => $this->input->post('is_promo_enabled',TRUE),
		'is_export_cost' => $this->input->post('is_export_cost',TRUE),
		'is_print_after_total' => $this->input->post('is_print_after_total',TRUE),
		'is_disable_change_price' => $this->input->post('is_disable_change_price',TRUE),
		'print_kitchen_id' => $this->input->post('print_kitchen_id',TRUE),
		'print_kitchen_section_id' => $this->input->post('print_kitchen_section_id',TRUE),
	    );
             
         }
            $this->Inv_outlet_menus_model->update($this->input->post('id', TRUE), $data);
           // echo $this->db->last_query();exit;
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('referensi/inv_outlet_menus'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Inv_outlet_menus_model->get_by_id($id);

        if ($row) {
            $this->Inv_outlet_menus_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('referensi/inv_outlet_menus'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/inv_outlet_menus'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
	/*$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('outlet_id', 'outlet id', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('menu_class_id', 'menu class id', 'trim|required');
	$this->form_validation->set_rules('menu_group_id', 'menu group id', 'trim|required');
	$this->form_validation->set_rules('menu_price', 'menu price', 'trim|required|numeric');
	$this->form_validation->set_rules('unit_cost', 'unit cost', 'trim|required|numeric');
	$this->form_validation->set_rules('product_id', 'product id', 'trim|required');
	$this->form_validation->set_rules('recipe_id', 'recipe id', 'trim|required');
	$this->form_validation->set_rules('recipe_qty', 'recipe qty', 'trim|required');
	$this->form_validation->set_rules('is_promo_enabled', 'is promo enabled', 'trim|required');
	$this->form_validation->set_rules('is_export_cost', 'is export cost', 'trim|required');
	$this->form_validation->set_rules('is_print_after_total', 'is print after total', 'trim|required');
	$this->form_validation->set_rules('is_disable_change_price', 'is disable change price', 'trim|required');
	$this->form_validation->set_rules('print_kitchen_id', 'print kitchen id', 'trim|required');
	$this->form_validation->set_rules('print_kitchen_section_id', 'print kitchen section id', 'trim|required');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');*/

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Inv_outlet_menus.php */
/* Location: ./application/controllers/Inv_outlet_menus.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 09:06:44 */
/* http://harviacode.com */