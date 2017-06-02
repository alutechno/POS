<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Mst_pos_tables extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Mst_pos_tables_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
		}
		public function index() {
			$this->load->view('mst_pos_tables/mst_pos_tables_list');
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->Mst_pos_tables_model->json();
		}
		public function read($id) {
			$row = $this->Mst_pos_tables_model->get_by_id($id);
			if ($row) {
				$data = array('id' => $row->id, 'outlet_id' => $row->outlet_id,
					'table_no' => $row->table_no, 'cover' => $row->cover,
					'section' => $row->section, 'remark' => $row->remark, 'status' => $row->status,
					'created_date' => $row->created_date, 'modified_date' => $row->modified_date,
					'created_by' => $row->created_by, 'modified_by' => $row->modified_by,);
				$this->load->view('mst_pos_tables/mst_pos_tables_read', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(site_url('mst_pos_tables'));
			}
		}
		public function create() {
			$data = array('button' => 'Create',
				'action' => site_url('mst_pos_tables/create_action'), 'id' => set_value('id'),
				'outlet_id' => set_value('outlet_id'), 'table_no' => set_value('table_no'),
				'cover' => set_value('cover'), 'section' => set_value('section'),
				'remark' => set_value('remark'), 'status' => set_value('status'),
				'created_date' => set_value('created_date'),
				'modified_date' => set_value('modified_date'),
				'created_by' => set_value('created_by'),
				'modified_by' => set_value('modified_by'),);
			$this->load->view('mst_pos_tables/mst_pos_tables_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('outlet_id' => $this->input->post('outlet_id', true),
					'table_no' => $this->input->post('table_no', true),
					'cover' => $this->input->post('cover', true),
					'section' => $this->input->post('section', true),
					'remark' => $this->input->post('remark', true),
					'status' => $this->input->post('status', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_pos_tables_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(site_url('mst_pos_tables'));
			}
		}
		public function update($id) {
			$row = $this->Mst_pos_tables_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update',
					'action' => site_url('mst_pos_tables/update_action'),
					'id' => set_value('id', $row->id),
					'outlet_id' => set_value('outlet_id', $row->outlet_id),
					'table_no' => set_value('table_no', $row->table_no),
					'cover' => set_value('cover', $row->cover),
					'section' => set_value('section', $row->section),
					'remark' => set_value('remark', $row->remark),
					'status' => set_value('status', $row->status),
					'created_date' => set_value('created_date', $row->created_date),
					'modified_date' => set_value('modified_date', $row->modified_date),
					'created_by' => set_value('created_by', $row->created_by),
					'modified_by' => set_value('modified_by', $row->modified_by),);
				$this->load->view('mst_pos_tables/mst_pos_tables_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(site_url('mst_pos_tables'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('outlet_id' => $this->input->post('outlet_id', true),
					'table_no' => $this->input->post('table_no', true),
					'cover' => $this->input->post('cover', true),
					'section' => $this->input->post('section', true),
					'remark' => $this->input->post('remark', true),
					'status' => $this->input->post('status', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_pos_tables_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(site_url('mst_pos_tables'));
			}
		}
		public function delete($id) {
			$row = $this->Mst_pos_tables_model->get_by_id($id);
			if ($row) {
				$this->Mst_pos_tables_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(site_url('mst_pos_tables'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(site_url('mst_pos_tables'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('outlet_id', 'outlet id', 'trim|required');
			$this->form_validation->set_rules('table_no', 'table no', 'trim|required');
			$this->form_validation->set_rules('cover', 'cover', 'trim|required');
			$this->form_validation->set_rules('section', 'section', 'trim|required');
			$this->form_validation->set_rules('remark', 'remark', 'trim|required');
			$this->form_validation->set_rules('status', 'status', 'trim|required');
			$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
			$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
			$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
			$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
	}

	/* End of file Mst_pos_tables.php */
	/* Location: ./application/controllers/Mst_pos_tables.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-23 09:26:34 */
	/* http://harviacode.com */
