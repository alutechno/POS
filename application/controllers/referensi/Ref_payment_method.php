<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ref_payment_method extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ref_payment_method_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
        is_logged_in();
    }

    public function index()
    {
        $data['title']="Payment Method";
        datatables('referensi/ref_payment_method/ref_payment_method_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Ref_payment_method_model->json();
    }

    public function read($id) 
    {
        $row = $this->Ref_payment_method_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'code' => $row->code,
                'title'=>'Payment Method',
		'name' => $row->name,
		'description' => $row->description,
		'status' => $row->status,
		'credit_limit' => $row->credit_limit,
		'is_credit_card' => $row->is_credit_card,
		'account_id' => $row->account_id,
		'created_date' => $row->created_date,
		'modified_date' => $row->modified_date,
		'created_by' => $row->created_by,
		'modified_by' => $row->modified_by,
	    );
            $this->load->view('ref_payment_method/ref_payment_method_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('ref_payment_method'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => base_url('referensi/ref_payment_method/create_action'),
	    'id' => set_value('id'),
            'title'=>'Payment Method',
	    'code' => set_value('code'),
	    'name' => set_value('name'),
	    'description' => set_value('description'),
	    'status' => set_value('status'),
	    'credit_limit' => set_value('credit_limit'),
	    'is_credit_card' => set_value('is_credit_card'),
	    'account_id' => set_value('account_id'),
	
	);
        
       form('referensi/ref_payment_method/ref_payment_method_form', $data);
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
		'credit_limit' => $this->input->post('credit_limit',TRUE),
		'is_credit_card' => $this->input->post('is_credit_card',TRUE),
		'account_id' => $this->input->post('account_id',TRUE),
		
	    );

            $this->Ref_payment_method_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('referensi/ref_payment_method'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Ref_payment_method_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'title'=>'Payment Method',
                'action' => base_url('referensi/ref_payment_method/update_action'),
		'id' => set_value('id', $row->id),
		'code' => set_value('code', $row->code),
		'name' => set_value('name', $row->name),
		'description' => set_value('description', $row->description),
		'status' => set_value('status', $row->status),
		'credit_limit' => set_value('credit_limit', $row->credit_limit),
		'is_credit_card' => set_value('is_credit_card', $row->is_credit_card),
		'account_id' => set_value('account_id', $row->account_id),
	    );
            form('referensi/ref_payment_method/ref_payment_method_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/ref_payment_method'));
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
		'credit_limit' => $this->input->post('credit_limit',TRUE),
		'is_credit_card' => $this->input->post('is_credit_card',TRUE),
		'account_id' => $this->input->post('account_id',TRUE),
	    );

            $this->Ref_payment_method_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('referensi/ref_payment_method'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Ref_payment_method_model->get_by_id($id);

        if ($row) {
            $this->Ref_payment_method_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('referensi/ref_payment_method'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('referensi/ref_payment_method'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('code', 'code', 'trim|required');
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('description', 'description', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	//$this->form_validation->set_rules('credit_limit', 'credit limit', 'trim|required|numeric');
	//$this->form_validation->set_rules('is_credit_card', 'is credit card', 'trim|required');
	//$this->form_validation->set_rules('account_id', 'account id', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Ref_payment_method.php */
/* Location: ./application/controllers/Ref_payment_method.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 17:07:53 */
/* http://harviacode.com */