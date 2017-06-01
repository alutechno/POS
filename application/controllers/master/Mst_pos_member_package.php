<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mst_pos_member_package extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Mst_pos_member_package_model');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		is_logged_in();
	}

	public function index() {
		$data['title'] = 'Pos Member Package';
		datatables('master/mst_pos_member_package/mst_pos_member_package_list', $data);
	}

	public function json() {
		header('Content-Type: application/json');
		echo $this->Mst_pos_member_package_model->json();
	}

	public function read($id) {
		$row = $this->Mst_pos_member_package_model->get_by_id($id);
		if ($row) {
			$data = array('id' => $row->id, 'code' => $row->code, 'title' => 'Pos Member Package', 'name' => $row->name, 'short_name' => $row->short_name, 'description' => $row->description, 'status' => $row->status, 'percent_food' => $row->percent_food, 'percent_beverage' => $row->percent_beverage, 'percent_others' => $row->percent_others, 'created_date' => $row->created_date, 'modified_date' => $row->modified_date, 'created_by' => $row->created_by, 'modified_by' => $row->modified_by,);
			form('mst_pos_member_package/mst_pos_member_package_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('master/mst_pos_member_package'));
		}
	}

	public function create() {
		$data = array('button' => 'Create', 'action' => base_url('master/mst_pos_member_package/create_action'), 'title' => 'Pos Member Package', 'id' => set_value('id'), 'code' => set_value('code'), 'name' => set_value('name'), 'short_name' => set_value('short_name'), 'description' => set_value('description'), 'status' => set_value('status'), 'percent_food' => set_value('percent_food'), 'percent_beverage' => set_value('percent_beverage'), 'percent_others' => set_value('percent_others'),);

		form('master/mst_pos_member_package/mst_pos_member_package_form', $data);
	}

	public function create_action() {
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array('code' => $this->input->post('code', TRUE), 'name' => $this->input->post('name', TRUE), 'short_name' => $this->input->post('short_name', TRUE), 'description' => $this->input->post('description', TRUE), 'status' => $this->input->post('status', TRUE), 'percent_food' => $this->input->post('percent_food', TRUE), 'percent_beverage' => $this->input->post('percent_beverage', TRUE), 'percent_others' => $this->input->post('percent_others', TRUE),

			);

			$this->Mst_pos_member_package_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(base_url('master/mst_pos_member_package'));
		}
	}

	public function update($id) {
		$row = $this->Mst_pos_member_package_model->get_by_id($id);

		if ($row) {
			$data = array('button' => 'Update', 'action' => base_url('mst_pos_member_package/update_action'), 'id' => set_value('id', $row->id), 'code' => set_value('code', $row->code), 'name' => set_value('name', $row->name), 'short_name' => set_value('short_name', $row->short_name), 'description' => set_value('description', $row->description), 'status' => set_value('status', $row->status), 'percent_food' => set_value('percent_food', $row->percent_food), 'percent_beverage' => set_value('percent_beverage', $row->percent_beverage), 'percent_others' => set_value('percent_others', $row->percent_others),

			);
			form('master/mst_pos_member_package/mst_pos_member_package_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('master/mst_pos_member_package'));
		}
	}

	public function update_action() {
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array('code' => $this->input->post('code', TRUE), 'name' => $this->input->post('name', TRUE), 'short_name' => $this->input->post('short_name', TRUE), 'description' => $this->input->post('description', TRUE), 'status' => $this->input->post('status', TRUE), 'percent_food' => $this->input->post('percent_food', TRUE), 'percent_beverage' => $this->input->post('percent_beverage', TRUE), 'percent_others' => $this->input->post('percent_others', TRUE),);

			$this->Mst_pos_member_package_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(base_url('master/mst_pos_member_package'));
		}
	}

	public function delete($id) {
		$row = $this->Mst_pos_member_package_model->get_by_id($id);

		if ($row) {
			$this->Mst_pos_member_package_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(base_url('master/mst_pos_member_package'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('master/mst_pos_member_package'));
		}
	}

	public function _rules() {
		$this->form_validation->set_rules('code', 'code', 'trim|required');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
		/*$this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('percent_food', 'percent food', 'trim|required|numeric');
        $this->form_validation->set_rules('percent_beverage', 'percent beverage', 'trim|required|numeric');
        $this->form_validation->set_rules('percent_others', 'percent others', 'trim|required|numeric');
        $this->form_validation->set_rules('created_date', 'created date', 'trim|required');
        $this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
        $this->form_validation->set_rules('created_by', 'created by', 'trim|required');
        $this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
    */
		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

}

/* End of file Mst_pos_member_package.php */
/* Location: ./application/controllers/Mst_pos_member_package.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-21 09:54:28 */
/* http://harviacode.com */
