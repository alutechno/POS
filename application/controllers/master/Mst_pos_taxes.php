<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_pos_taxes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mst_pos_taxes_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
           is_logged_in();
    }

    public function index()
    {
        $data['title']='Tax';
        datatables('master/mst_pos_taxes/mst_pos_taxes_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Mst_pos_taxes_model->json();
    }

    public function read($id) 
    {
        $row = $this->Mst_pos_taxes_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
		'name' => $row->name,
		'description' => $row->description,
		'tax_percent' => $row->tax_percent,
		'status' => $row->status,
		
	    );
            $this->load->view('mst_pos_taxes/mst_pos_taxes_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('mst_pos_taxes'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => base_url('mst_pos_taxes/create_action'),
	    'id' => set_value('id'),
            'title'=>'Tax',
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'description' => set_value('description'),
	    'tax_percent' => set_value('tax_percent'),
	    'status' => set_value('status'),
	  
	);
        form('master/mst_pos_taxes/mst_pos_taxes_form', $data);
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
		'tax_percent' => $this->input->post('tax_percent',TRUE),
		'status' => $this->input->post('status',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_pos_taxes_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('master/mst_pos_taxes'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mst_pos_taxes_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Tax',
                'action' => base_url('master/mst_pos_taxes/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'description' => set_value('description', $row->description),
		'tax_percent' => set_value('tax_percent', $row->tax_percent),
		'status' => set_value('status', $row->status),
		
	    );
            form('master/mst_pos_taxes/mst_pos_taxes_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_pos_taxes'));
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
		'tax_percent' => $this->input->post('tax_percent',TRUE),
		'status' => $this->input->post('status',TRUE),
		'created_date' => $this->input->post('created_date',TRUE),
		'modified_date' => $this->input->post('modified_date',TRUE),
		'created_by' => $this->input->post('created_by',TRUE),
		'modified_by' => $this->input->post('modified_by',TRUE),
	    );

            $this->Mst_pos_taxes_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('master/mst_pos_taxes'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mst_pos_taxes_model->get_by_id($id);

        if ($row) {
            $this->Mst_pos_taxes_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('master/mst_pos_taxes'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('master/mst_pos_taxes'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	/*$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('tax_percent', 'tax percent', 'trim|required|numeric');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
	$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
	$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
	$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
        */
        
	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Mst_pos_taxes.php */
/* Location: ./application/controllers/Mst_pos_taxes.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 17:48:04 */
/* http://harviacode.com */