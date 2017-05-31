<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_house_use extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mst_house_use_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
          is_logged_in();
    }

    public function index()
    {
        $data['title']='Hause Use';
        datatables('master/mst_house_use/mst_house_use_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Mst_house_use_model->json();
    }

    public function read($id) 
    {
        $row = $this->Mst_house_use_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
		'name' => $row->name,
		'short_name' => $row->short_name,
		'description' => $row->description,
		'status' => $row->status,
		'pos_cost_center_id' => $row->pos_cost_center_id,
		'max_spent_monthly' => $row->max_spent_monthly,
		'created_date' => $row->created_date,
		'modified_date' => $row->modified_date,
		'created_by' => $row->created_by,
		'modified_by' => $row->modified_by,
	    );
            $this->load->view('mst_house_use/mst_house_use_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_house_use'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'title'=>'House',
            'action' => base_url('master/mst_house_use/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'short_name' => set_value('short_name'),
	    'description' => set_value('description'),
	    'status' => set_value('status'),
	    'pos_cost_center_id' => set_value('pos_cost_center_id'),
	    'max_spent_monthly' => set_value('max_spent_monthly'),
	    'created_date' => set_value('created_date'),
	    'modified_date' => set_value('modified_date'),
	    'created_by' => set_value('created_by'),
	    'modified_by' => set_value('modified_by'),
	);
        form('master/mst_house_use/mst_house_use_form', $data);
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
		'status' => $this->input->post('status',TRUE),
		'pos_cost_center_id' => $this->input->post('pos_cost_center_id',TRUE),
		'max_spent_monthly' => $this->input->post('max_spent_monthly',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_house_use_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('master/mst_house_use'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mst_house_use_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Hause Use',
                'action' => base_url('master/mst_house_use/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'short_name' => set_value('short_name', $row->short_name),
		'description' => set_value('description', $row->description),
		'status' => set_value('status', $row->status),
		'pos_cost_center_id' => set_value('pos_cost_center_id', $row->pos_cost_center_id),
		'max_spent_monthly' => set_value('max_spent_monthly', $row->max_spent_monthly),
		'created_date' => set_value('created_date', $row->created_date),
		'modified_date' => set_value('modified_date', $row->modified_date),
		'created_by' => set_value('created_by', $row->created_by),
		'modified_by' => set_value('modified_by', $row->modified_by),
	    );
            form('master/mst_house_use/mst_house_use_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_house_use'));
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
		'status' => $this->input->post('status',TRUE),
		'pos_cost_center_id' => $this->input->post('pos_cost_center_id',TRUE),
		'max_spent_monthly' => $this->input->post('max_spent_monthly',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_house_use_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('master/mst_house_use'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mst_house_use_model->get_by_id($id);

        if ($row) {
            $this->Mst_house_use_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('master/mst_house_use'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_house_use'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
	/*$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('pos_cost_center_id', 'pos cost center id', 'trim|required');
	$this->form_validation->set_rules('max_spent_monthly', 'max spent monthly', 'trim|required|numeric');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');*/

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Mst_house_use.php */
/* Location: ./application/controllers/Mst_house_use.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 18:59:00 */
/* http://harviacode.com */