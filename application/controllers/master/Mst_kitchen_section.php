<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Mst_kitchen_section extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Mst_kitchen_section_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
			is_logged_in();
		}
		public function index() {
			$data['title'] = 'Kitchen Section';
			datatables('master/mst_kitchen_section/mst_kitchen_section_list', $data);
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->Mst_kitchen_section_model->json();
		}
		public function read($id) {
			$row = $this->Mst_kitchen_section_model->get_by_id($id);
			if ($row) {
				$data = array('id' => $row->id, 'kitchen_id' => $row->kitchen_id,
					'code' => $row->code, 'name' => $row->name, 'short_name' => $row->short_name,
					'description' => $row->description, 'status' => $row->status,);
				$this->load->view('master/mst_kitchen_section/mst_kitchen_section_read', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_kitchen_section'));
			}
		}
		public function create() {
			$data = array('button' => 'Create', 'title' => 'Kitchen Section',
				'action' => base_url('master/mst_kitchen_section/create_action'),
				'id' => set_value('id'), 'kitchen_id' => set_value('kitchen_id'),
				'code' => set_value('code'), 'name' => set_value('name'),
				'short_name' => set_value('short_name'), 'description' => set_value('description'),
				'status' => set_value('status'),
			);
			form('master/mst_kitchen_section/mst_kitchen_section_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('kitchen_id' => $this->input->post('kitchen_id', true),
					'code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'short_name' => $this->input->post('short_name', true),
					'description' => $this->input->post('description', true),
					'status' => $this->input->post('status', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_kitchen_section_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(site_url('mst_kitchen_section'));
			}
		}
		public function update($id) {
			$row = $this->Mst_kitchen_section_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update',
					'action' => site_url('mst_kitchen_section/update_action'),
					'id' => set_value('id', $row->id),
					'kitchen_id' => set_value('kitchen_id', $row->kitchen_id),
					'code' => set_value('code', $row->code),
					'name' => set_value('name', $row->name),
					'short_name' => set_value('short_name', $row->short_name),
					'description' => set_value('description', $row->description),
					'status' => set_value('status', $row->status),
					'created_date' => set_value('created_date', $row->created_date),
					'modified_date' => set_value('modified_date', $row->modified_date),
					'created_by' => set_value('created_by', $row->created_by),
					'modified_by' => set_value('modified_by', $row->modified_by),);
				$this->load->view('mst_kitchen_section/mst_kitchen_section_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(site_url('mst_kitchen_section'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('kitchen_id' => $this->input->post('kitchen_id', true),
					'code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'short_name' => $this->input->post('short_name', true),
					'description' => $this->input->post('description', true),
					'status' => $this->input->post('status', true),
					'created_date' => $this->input->post('created_date', true),
					'modified_date' => $this->input->post('modified_date', true),
					'created_by' => $this->input->post('created_by', true),
					'modified_by' => $this->input->post('modified_by', true),);
				$this->Mst_kitchen_section_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(site_url('mst_kitchen_section'));
			}
		}
		public function delete($id) {
			$row = $this->Mst_kitchen_section_model->get_by_id($id);
			if ($row) {
				$this->Mst_kitchen_section_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(site_url('mst_kitchen_section'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(site_url('mst_kitchen_section'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('kitchen_id', 'kitchen id', 'trim|required');
			$this->form_validation->set_rules('code', 'code', 'trim|required');
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
			$this->form_validation->set_rules('description', 'description', 'trim|required');
			$this->form_validation->set_rules('status', 'status', 'trim|required');
			$this->form_validation->set_rules('created_date', 'created date', 'trim|required');
			$this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
			$this->form_validation->set_rules('created_by', 'created by', 'trim|required');
			$this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
	}

	/* End of file Mst_kitchen_section.php */
	/* Location: ./application/controllers/Mst_kitchen_section.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-20 23:08:43 */
	/* http://harviacode.com */
