<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ref_meal_time extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ref_meal_time_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
               is_logged_in();
    }

    public function index()
    {
        $data['title']="Meal Time";
        datatables('referensi/ref_meal_time/ref_meal_time_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Ref_meal_time_model->json();
    }

    public function read($id) 
    {
        $row = $this->Ref_meal_time_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
		'name' => $row->name,
		'description' => $row->description,
		'status' => $row->status,
		'is_currently_active' => $row->is_currently_active,
		'created_date' => $row->created_date,
		'modified_date' => $row->modified_date,
		'created_by' => $row->created_by,
		'modified_by' => $row->modified_by,
	    );
            $this->load->view('ref_meal_time/ref_meal_time_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ref_meal_time'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
             'title'=>'',
            'action' => base_url('referensi/ref_meal_time/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'description' => set_value('description'),
	    'status' => set_value('status'),
	);
        
        form('referensi/ref_meal_time/ref_meal_time_form', $data);
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
		'is_currently_active' => $this->input->post('is_currently_active',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Ref_meal_time_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('ref_meal_time'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Ref_meal_time_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'',
                'action' => base_url('referensi/ref_meal_time/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'description' => set_value('description', $row->description),
		'status' => set_value('status', $row->status),
	    );
            form('referensi/ref_meal_time/ref_meal_time_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/ref_meal_time'));
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
		'is_currently_active' => $this->input->post('is_currently_active',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Ref_meal_time_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('ref_meal_time'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Ref_meal_time_model->get_by_id($id);

        if ($row) {
            $this->Ref_meal_time_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('ref_meal_time'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ref_meal_time'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('is_currently_active', 'is currently active', 'trim|required');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Ref_meal_time.php */
/* Location: ./application/controllers/Ref_meal_time.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-20 16:06:48 */
/* http://harviacode.com */