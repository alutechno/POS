<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pos_menus_promos extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pos_menus_promos_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
              is_logged_in();
    }

    public function index()
    {
        $data['title']='Menu Promotion';
        datatables('referensi/pos_menus_promos/pos_menus_promos_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Pos_menus_promos_model->json();
    }

    public function read($id) 
    {
        $row = $this->Pos_menus_promos_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'outlet_menu_id' => $row->outlet_menu_id,
		'name' => $row->name,
		'description' => $row->description,
		'begin_date' => $row->begin_date,
		'end_date' => $row->end_date,
		'begin_time' => $row->begin_time,
		'end_time' => $row->end_time,
		'discount_id' => $row->discount_id,
		'discount_percent' => $row->discount_percent,
		'discount_amount' => $row->discount_amount,
		'promo_price' => $row->promo_price,
		'is_avail_sunday' => $row->is_avail_sunday,
		'is_avail_monday' => $row->is_avail_monday,
		'is_avail_tuesday' => $row->is_avail_tuesday,
		'is_avail_wednesday' => $row->is_avail_wednesday,
		'is_avail_thursday' => $row->is_avail_thursday,
		'is_avail_friday' => $row->is_avail_friday,
		'is_avail_saturday' => $row->is_avail_saturday,
		'created_date' => $row->created_date,
		'modified_date' => $row->modified_date,
		'created_by' => $row->created_by,
		'modified_by' => $row->modified_by,
	    );
            $this->load->view('pos_menus_promos/pos_menus_promos_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('pos_menus_promos'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => base_url('referensi/pos_menus_promos/create_action'),
            'title'=>'Promos',
	    'id' => set_value('id'),
	    'outlet_menu_id' => set_value('outlet_menu_id'),
	    'name' => set_value('name'),
	    'description' => set_value('description'),
	    'begin_date' => set_value('begin_date'),
	    'end_date' => set_value('end_date'),
	    'begin_time' => set_value('begin_time'),
	    'end_time' => set_value('end_time'),
	    'discount_id' => set_value('discount_id'),
	    'discount_percent' => set_value('discount_percent'),
	    'discount_amount' => set_value('discount_amount'),
	    'promo_price' => set_value('promo_price'),
	    'is_avail_sunday' => set_value('is_avail_sunday'),
	    'is_avail_monday' => set_value('is_avail_monday'),
	    'is_avail_tuesday' => set_value('is_avail_tuesday'),
	    'is_avail_wednesday' => set_value('is_avail_wednesday'),
	    'is_avail_thursday' => set_value('is_avail_thursday'),
	    'is_avail_friday' => set_value('is_avail_friday'),
	    'is_avail_saturday' => set_value('is_avail_saturday'),
	    
	);
       form('referensi/pos_menus_promos/pos_menus_promos_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'outlet_menu_id' => $this->input->post('outlet_menu_id',TRUE),
		'name' => $this->input->post('name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'begin_date' => $this->input->post('begin_date',TRUE),
		'end_date' => $this->input->post('end_date',TRUE),
		'begin_time' => $this->input->post('begin_time',TRUE),
		'end_time' => $this->input->post('end_time',TRUE),
		'discount_id' => $this->input->post('discount_id',TRUE),
		'discount_percent' => $this->input->post('discount_percent',TRUE),
		'discount_amount' => $this->input->post('discount_amount',TRUE),
		'promo_price' => $this->input->post('promo_price',TRUE),
		'is_avail_sunday' => $this->input->post('is_avail_sunday',TRUE),
		'is_avail_monday' => $this->input->post('is_avail_monday',TRUE),
		'is_avail_tuesday' => $this->input->post('is_avail_tuesday',TRUE),
		'is_avail_wednesday' => $this->input->post('is_avail_wednesday',TRUE),
		'is_avail_thursday' => $this->input->post('is_avail_thursday',TRUE),
		'is_avail_friday' => $this->input->post('is_avail_friday',TRUE),
		'is_avail_saturday' => $this->input->post('is_avail_saturday',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Pos_menus_promos_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('pos_menus_promos'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Pos_menus_promos_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => base_url('pos_menus_promos/update_action'),
		'id' => set_value('id', $row->id),
		'outlet_menu_id' => set_value('outlet_menu_id', $row->outlet_menu_id),
		'name' => set_value('name', $row->name),
		'description' => set_value('description', $row->description),
		'begin_date' => set_value('begin_date', $row->begin_date),
		'end_date' => set_value('end_date', $row->end_date),
		'begin_time' => set_value('begin_time', $row->begin_time),
		'end_time' => set_value('end_time', $row->end_time),
		'discount_id' => set_value('discount_id', $row->discount_id),
		'discount_percent' => set_value('discount_percent', $row->discount_percent),
		'discount_amount' => set_value('discount_amount', $row->discount_amount),
		'promo_price' => set_value('promo_price', $row->promo_price),
		'is_avail_sunday' => set_value('is_avail_sunday', $row->is_avail_sunday),
		'is_avail_monday' => set_value('is_avail_monday', $row->is_avail_monday),
		'is_avail_tuesday' => set_value('is_avail_tuesday', $row->is_avail_tuesday),
		'is_avail_wednesday' => set_value('is_avail_wednesday', $row->is_avail_wednesday),
		'is_avail_thursday' => set_value('is_avail_thursday', $row->is_avail_thursday),
		'is_avail_friday' => set_value('is_avail_friday', $row->is_avail_friday),
		'is_avail_saturday' => set_value('is_avail_saturday', $row->is_avail_saturday),
		
	    );
           form('referensi/pos_menus_promos/pos_menus_promos_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/pos_menus_promos'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'outlet_menu_id' => $this->input->post('outlet_menu_id',TRUE),
		'name' => $this->input->post('name',TRUE),
		'description' => $this->input->post('description',TRUE),
		'begin_date' => $this->input->post('begin_date',TRUE),
		'end_date' => $this->input->post('end_date',TRUE),
		'begin_time' => $this->input->post('begin_time',TRUE),
		'end_time' => $this->input->post('end_time',TRUE),
		'discount_id' => $this->input->post('discount_id',TRUE),
		'discount_percent' => $this->input->post('discount_percent',TRUE),
		'discount_amount' => $this->input->post('discount_amount',TRUE),
		'promo_price' => $this->input->post('promo_price',TRUE),
		'is_avail_sunday' => $this->input->post('is_avail_sunday',TRUE),
		'is_avail_monday' => $this->input->post('is_avail_monday',TRUE),
		'is_avail_tuesday' => $this->input->post('is_avail_tuesday',TRUE),
		'is_avail_wednesday' => $this->input->post('is_avail_wednesday',TRUE),
		'is_avail_thursday' => $this->input->post('is_avail_thursday',TRUE),
		'is_avail_friday' => $this->input->post('is_avail_friday',TRUE),
		'is_avail_saturday' => $this->input->post('is_avail_saturday',TRUE),
		
	    );

            $this->Pos_menus_promos_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('referensi/pos_menus_promos'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Pos_menus_promos_model->get_by_id($id);

        if ($row) {
            $this->Pos_menus_promos_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('referensi/pos_menus_promos'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/pos_menus_promos'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('outlet_menu_id', 'outlet menu id', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('begin_date', 'begin date', 'trim|required');
	$this->form_validation->set_rules('end_date', 'end date', 'trim|required');
	$this->form_validation->set_rules('begin_time', 'begin time', 'trim|required');
	$this->form_validation->set_rules('end_time', 'end time', 'trim|required');
	$this->form_validation->set_rules('discount_id', 'discount id', 'trim|required');
	$this->form_validation->set_rules('discount_percent', 'discount percent', 'trim|required|numeric');
	$this->form_validation->set_rules('discount_amount', 'discount amount', 'trim|required|numeric');
	$this->form_validation->set_rules('promo_price', 'promo price', 'trim|required|numeric');
	$this->form_validation->set_rules('is_avail_sunday', 'is avail sunday', 'trim|required');
	$this->form_validation->set_rules('is_avail_monday', 'is avail monday', 'trim|required');
	$this->form_validation->set_rules('is_avail_tuesday', 'is avail tuesday', 'trim|required');
	$this->form_validation->set_rules('is_avail_wednesday', 'is avail wednesday', 'trim|required');
	$this->form_validation->set_rules('is_avail_thursday', 'is avail thursday', 'trim|required');
	$this->form_validation->set_rules('is_avail_friday', 'is avail friday', 'trim|required');
	$this->form_validation->set_rules('is_avail_saturday', 'is avail saturday', 'trim|required');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Pos_menus_promos.php */
/* Location: ./application/controllers/Pos_menus_promos.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-23 16:10:29 */
/* http://harviacode.com */