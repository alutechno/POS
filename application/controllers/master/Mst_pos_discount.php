<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_pos_discount extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mst_pos_discount_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
          is_logged_in();
    }

    public function index()
    {
        $data['title']='Diskon';
        datatables('master/mst_pos_discount/mst_pos_discount_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Mst_pos_discount_model->json();
    }

    public function read($id) 
    {
        $row = $this->Mst_pos_discount_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
                'title'=>'Diskon',
		'name' => $row->name,
		'description' => $row->description,
		'status' => $row->status,
		'food' => $row->food,
		'beverage' => $row->beverage,
		'others' => $row->others,
		'created_date' => $row->created_date,
		'modified_date' => $row->modified_date,
		'created_by' => $row->created_by,
		'modified_by' => $row->modified_by,
	    );
            $this->load->view('master/mst_pos_discount/mst_pos_discount_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_pos_discount'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'title'=>'Diskon',
            'action' => base_url('master/mst_pos_discount/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'description' => set_value('description'),
	    'status' => set_value('status'),
	    'food' => set_value('food'),
	    'beverage' => set_value('beverage'),
	    'others' => set_value('others'),
	    'created_date' => set_value('created_date'),
	    'modified_date' => set_value('modified_date'),
	    'created_by' => set_value('created_by'),
	    'modified_by' => set_value('modified_by'),
	);
      form('master/mst_pos_discount/mst_pos_discount_form', $data);
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
		'description' => $this->input->post('description',TRUE),
		'status' => $this->input->post('status',TRUE),
		'food' => $this->input->post('food',TRUE),
		'beverage' => $this->input->post('beverage',TRUE),
		'others' => $this->input->post('others',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_pos_discount_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('master/mst_pos_discount'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mst_pos_discount_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Diskon',
                'action' => base_url('master/mst_pos_discount/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'description' => set_value('description', $row->description),
		'status' => set_value('status', $row->status),
		'food' => set_value('food', $row->food),
		'beverage' => set_value('beverage', $row->beverage),
		'others' => set_value('others', $row->others),
		'created_date' => set_value('created_date', $row->created_date),
		'modified_date' => set_value('modified_date', $row->modified_date),
		'created_by' => set_value('created_by', $row->created_by),
		'modified_by' => set_value('modified_by', $row->modified_by),
	    );
           form('master/mst_pos_discount/mst_pos_discount_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_pos_discount'));
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
		'description' => $this->input->post('description',TRUE),
		'status' => $this->input->post('status',TRUE),
		'food' => $this->input->post('food',TRUE),
		'beverage' => $this->input->post('beverage',TRUE),
		'others' => $this->input->post('others',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_pos_discount_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('master/mst_pos_discount'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mst_pos_discount_model->get_by_id($id);

        if ($row) {
            $this->Mst_pos_discount_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('master/mst_pos_discount'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_pos_discount'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	/*$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('food', 'food', 'trim|required|numeric');
	$this->form_validation->set_rules('beverage', 'beverage', 'trim|required|numeric');
	$this->form_validation->set_rules('others', 'others', 'trim|required|numeric');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
        */
        
	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Mst_pos_discount.php */
/* Location: ./application/controllers/Mst_pos_discount.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 19:16:23 */
/* http://harviacode.com */