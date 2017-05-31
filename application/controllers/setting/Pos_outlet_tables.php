<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pos_outlet_tables extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Pos_outlet_tables_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
           is_logged_in();
    }

    public function index()
    {
        $data['title']="Outlet Table";
        datatables('setting/pos_outlet_tables/pos_outlet_tables_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Pos_outlet_tables_model->json();
    }

    

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => base_url('setting/pos_outlet_tables/create_action'),
            'title'=>'Outlet Tables',
	    'id' => set_value('id'),
	    'name' => set_value('name'),
	    'zone_id' => set_value('zone_id'),
	);
        
        form('setting/pos_outlet_tables/pos_outlet_tables_form', $data);
    }
    
    public function create_action() 
    {
       // echo $this->input->post('name',TRUE);exit;
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
		'zone_id' => $this->input->post('zone_id',TRUE),
	    );

            $this->Pos_outlet_tables_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(base_url('setting/pos_outlet_tables'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Pos_outlet_tables_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                 'title'=>'Outlet Tables',
                'action' => base_url('setting/pos_outlet_tables/update_action'),
		'id' => set_value('id', $row->id),
		'name' => set_value('name', $row->name),
		'zone_id' => set_value('zone_id', $row->zone_id),
	    );
            
            form('setting/pos_outlet_tables/pos_outlet_tables_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('setting/pos_outlet_tables'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
		'zone_id' => $this->input->post('zone_id',TRUE),
	    );

            $this->Pos_outlet_tables_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(base_url('setting/pos_outlet_tables'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Pos_outlet_tables_model->get_by_id($id);

        if ($row) {
            $this->Pos_outlet_tables_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(base_url('setting/pos_outlet_tables'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(base_url('setting/pos_outlet_tables'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('zone_id', 'zone id', 'trim|required');
	/*$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('time', 'time', 'trim|required');
	$this->form_validation->set_rules('store_id', 'store id', 'trim|required');
        */
	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Pos_outlet_tables.php */
/* Location: ./application/controllers/Pos_outlet_tables.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 02:42:38 */
/* http://harviacode.com */