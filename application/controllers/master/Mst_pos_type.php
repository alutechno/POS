<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Mst_pos_type extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Mst_pos_type_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
			is_logged_in();
		}
		public function index() {
			$data['title'] = "Pos Type";
			datatables('master/mst_pos_type/mst_pos_type_list', $data);
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->Mst_pos_type_model->json();
		}
		public function read($id) {
			$row = $this->Mst_pos_type_model->get_by_id($id);
			if ($row) {
				$data = array('id' => $row->id, 'code' => $row->code, 'name' => $row->name,
					'short_name' => $row->short_name, 'description' => $row->description,
					'status' => $row->status, 'outlet_id' => $row->outlet_id,
					'created_date' => $row->created_date, 'modified_date' => $row->modified_date,
					'created_by' => $row->created_by, 'modified_by' => $row->modified_by,);
				$this->load->view('master/mst_pos_type/mst_pos_type_read', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_pos_type'));
			}
		}
		public function create() {
			$data = array('button' => 'Create', 'title' => 'Pos Type',
				'action' => base_url('master/mst_pos_type/create_action'), 'id' => set_value('id'),
				'code' => set_value('code'), 'name' => set_value('name'),
				'short_name' => set_value('short_name'), 'description' => set_value('description'),
				'status' => set_value('status'), 'outlet_id' => set_value('outlet_id'),
				'created_date' => set_value('created_date'),
				'modified_date' => set_value('modified_date'),
				'created_by' => set_value('created_by'),
				'modified_by' => set_value('modified_by'),);
			form('master/mst_pos_type/mst_pos_type_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'short_name' => $this->input->post('short_name', true),
					'description' => $this->input->post('description', true),
					'status' => $this->input->post('status', true),
					'outlet_id' => $this->input->post('outlet_id', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_pos_type_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(base_url('mst_pos_type'));
			}
		}
		public function update($id) {
			$row = $this->Mst_pos_type_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update', 'title' => 'Pos Type',
					'action' => base_url('master/mst_pos_type/update_action'),
					'id' => set_value('id', $row->id), 'code' => set_value('code', $row->code),
					'name' => set_value('name', $row->name),
					'short_name' => set_value('short_name', $row->short_name),
					'description' => set_value('description', $row->description),
					'status' => set_value('status', $row->status),
					'outlet_id' => set_value('outlet_id', $row->outlet_id),
					'created_date' => set_value('created_date', $row->created_date),
					'modified_date' => set_value('modified_date', $row->modified_date),
					'created_by' => set_value('created_by', $row->created_by),
					'modified_by' => set_value('modified_by', $row->modified_by),);
				$this->load->view('master/mst_pos_type/mst_pos_type_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_pos_type'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'short_name' => $this->input->post('short_name', true),
					'description' => $this->input->post('description', true),
					'status' => $this->input->post('status', true),
					'outlet_id' => $this->input->post('outlet_id', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_pos_type_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(base_url('master/mst_pos_type'));
			}
		}
		public function delete($id) {
			$row = $this->Mst_pos_type_model->get_by_id($id);
			if ($row) {
				$this->Mst_pos_type_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(base_url('master/mst_pos_type'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_pos_type'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('code', 'code', 'trim|required');
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			/*$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            $this->form_validation->set_rules('status', 'status', 'trim|required');
            $this->form_validation->set_rules('outlet_id', 'outlet id', 'trim|required');
            $this->form_validation->set_rules('created_date', 'created date', 'trim|required');
            $this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
            $this->form_validation->set_rules('created_by', 'created by', 'trim|required');
            $this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
        */
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
	}

	/* End of file Mst_pos_type.php */
	/* Location: ./application/controllers/Mst_pos_type.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-08 19:26:49 */
	/* http://harviacode.com */
