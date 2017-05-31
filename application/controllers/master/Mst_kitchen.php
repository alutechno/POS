<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_kitchen extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mst_kitchen_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
          is_logged_in();
    }

    public function index()
    {
        $data['title']="Kitchen";
        datatables('master/mst_kitchen/mst_kitchen_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Mst_kitchen_model->json();
    }

    public function read($id) 
    {
        $row = $this->Mst_kitchen_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
		'name' => $row->name,
		'short_name' => $row->short_name,
		'description' => $row->description,
		'status' => $row->status,
		
	    );
            $this->load->view('mst_kitchen/mst_kitchen_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('mst_kitchen'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'title'=>'Kitchen',
            'action' => base_url('master/mst_kitchen/create_action'),
	    'id' => set_value('id'),
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'short_name' => set_value('short_name'),
	    'description' => set_value('description'),
	    'status' => set_value('status'),
	);
        form('master/mst_kitchen/mst_kitchen_form', $data);
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
	    );

            $this->Mst_kitchen_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('master/mst_kitchen'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mst_kitchen_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Kitchen',
                'action' => base_url('master/mst_kitchen/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'short_name' => set_value('short_name', $row->short_name),
		'description' => set_value('description', $row->description),
		'status' => set_value('status', $row->status),
		
	    );
            form('master/mst_kitchen/mst_kitchen_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_kitchen'));
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
	    );

            $this->Mst_kitchen_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('master/mst_kitchen'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mst_kitchen_model->get_by_id($id);

        if ($row) {
            $this->Mst_kitchen_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('master/mst_kitchen'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_kitchen'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');


	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Mst_kitchen.php */
/* Location: ./application/controllers/Mst_kitchen.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-20 22:49:38 */
/* http://harviacode.com */