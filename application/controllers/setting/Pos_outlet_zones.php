<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Pos_outlet_zones extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Pos_outlet_zones_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
			is_logged_in();
		}
		public function index() {
			$data['title'] = "Outlet Zone";
			// $this->load->view('setting/pos_outlet_zones/pos_outlet_zones_list');
			datatables('setting/pos_outlet_zones/pos_outlet_zones_list', $data);
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->Pos_outlet_zones_model->json();
		}
		public function create() {
			$data = array('button' => 'Create', 'title' => 'Outlet Zone',
				'action' => base_url('setting/pos_outlet_zones/create_action'),
				'id' => set_value('id'), 'store_id' => set_value('store_id'),
				'name' => set_value('name'),);
			form('setting/pos_outlet_zones/pos_outlet_zones_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('store_id' => $this->input->post('store_id', true),
					'name' => $this->input->post('name', true),);
				$this->Pos_outlet_zones_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(base_url('setting/pos_outlet_zones'));
			}
		}
		public function update($id) {
			$row = $this->Pos_outlet_zones_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update', 'title' => 'Outlet Zone',
					'action' => base_url('setting/pos_outlet_zones/update_action'),
					'id' => set_value('id', $row->id),
					'store_id' => set_value('store_id', $row->store_id),
					'name' => set_value('name', $row->name),);
				form('setting/pos_outlet_zones/pos_outlet_zones_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('pos_outlet_zones'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('store_id' => $this->input->post('store_id', true),
					'name' => $this->input->post('name', true),);
				$this->Pos_outlet_zones_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(base_url('setting/pos_outlet_zones'));
			}
		}
		public function delete($id) {
			$row = $this->Pos_outlet_zones_model->get_by_id($id);
			if ($row) {
				$this->Pos_outlet_zones_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(base_url('setting/pos_outlet_zones'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('setting/pos_outlet_zones'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('store_id', 'store id', 'trim|required');
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
	}

	/* End of file Pos_outlet_zones.php */
	/* Location: ./application/controllers/Pos_outlet_zones.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-06 17:23:32 */
	/* http://harviacode.com */
