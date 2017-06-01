<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ref_pos_segment extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Ref_pos_segment_model');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		is_logged_in();
	}

	public function index() {
		$data['title'] = "Post Segment";
		datatables('referensi/ref_pos_segment/ref_pos_segment_list', $data);
	}

	public function json() {
		header('Content-Type: application/json');
		echo $this->Ref_pos_segment_model->json();
	}

	public function read($id) {
		$row = $this->Ref_pos_segment_model->get_by_id($id);
		if ($row) {
			$data = array('id' => $row->id, 'code' => $row->code, 'name' => $row->name, 'short_name' => $row->short_name, 'description' => $row->description, 'status' => $row->status, 'is_cover' => $row->is_cover, 'is_captain_order' => $row->is_captain_order, 'is_phone_number' => $row->is_phone_number, 'is_waiter' => $row->is_waiter, 'is_autoincrement_tbl_no' => $row->is_autoincrement_tbl_no, 'is_guest_profile' => $row->is_guest_profile, 'is_reservation' => $row->is_reservation, 'is_rate_code' => $row->is_rate_code, 'is_service' => $row->is_service, 'is_tax' => $row->is_tax, 'surcharge_percent' => $row->surcharge_percent, 'min_charge' => $row->min_charge, 'hourly_charge' => $row->hourly_charge, 'is_food_entry' => $row->is_food_entry, 'is_beverage_alcohol' => $row->is_beverage_alcohol, 'is_beverage_non_alcohol' => $row->is_beverage_non_alcohol,

			);
			$this->load->view('referensi/ref_pos_segment/ref_pos_segment_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('referensi/ref_pos_segment'));
		}
	}

	public function create() {
		$data = array('button' => 'Create', 'action' => site_url('ref_pos_segment/create_action'), 'id' => set_value('id'), 'code' => set_value('code'), 'name' => set_value('name'), 'title' => 'Pos Segment', 'short_name' => set_value('short_name'), 'description' => set_value('description'), 'status' => set_value('status'), 'is_cover' => set_value('is_cover'), 'is_captain_order' => set_value('is_captain_order'), 'is_phone_number' => set_value('is_phone_number'), 'is_waiter' => set_value('is_waiter'), 'is_autoincrement_tbl_no' => set_value('is_autoincrement_tbl_no'), 'is_guest_profile' => set_value('is_guest_profile'), 'is_reservation' => set_value('is_reservation'), 'is_rate_code' => set_value('is_rate_code'), 'is_service' => set_value('is_service'), 'is_tax' => set_value('is_tax'), 'surcharge_percent' => set_value('surcharge_percent'), 'min_charge' => set_value('min_charge'), 'hourly_charge' => set_value('hourly_charge'), 'is_food_entry' => set_value('is_food_entry'), 'is_beverage_alcohol' => set_value('is_beverage_alcohol'), 'is_beverage_non_alcohol' => set_value('is_beverage_non_alcohol'),);

		form_col2('referensi/ref_pos_segment/ref_pos_segment_form', $data);
	}

	public function create_action() {
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array('code' => $this->input->post('code', TRUE), 'name' => $this->input->post('name', TRUE), 'short_name' => $this->input->post('short_name', TRUE), 'description' => $this->input->post('description', TRUE), 'status' => $this->input->post('status', TRUE), 'is_cover' => $this->input->post('is_cover', TRUE), 'is_captain_order' => $this->input->post('is_captain_order', TRUE), 'is_phone_number' => $this->input->post('is_phone_number', TRUE), 'is_waiter' => $this->input->post('is_waiter', TRUE), 'is_autoincrement_tbl_no' => $this->input->post('is_autoincrement_tbl_no', TRUE), 'is_guest_profile' => $this->input->post('is_guest_profile', TRUE), 'is_reservation' => $this->input->post('is_reservation', TRUE), 'is_rate_code' => $this->input->post('is_rate_code', TRUE), 'is_service' => $this->input->post('is_service', TRUE), 'is_tax' => $this->input->post('is_tax', TRUE), 'surcharge_percent' => $this->input->post('surcharge_percent', TRUE), 'min_charge' => $this->input->post('min_charge', TRUE), 'hourly_charge' => $this->input->post('hourly_charge', TRUE), 'is_food_entry' => $this->input->post('is_food_entry', TRUE), 'is_beverage_alcohol' => $this->input->post('is_beverage_alcohol', TRUE), 'is_beverage_non_alcohol' => $this->input->post('is_beverage_non_alcohol', TRUE),);

			$this->Ref_pos_segment_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(base_url('referensi/ref_pos_segment'));
		}
	}

	public function update($id) {
		$row = $this->Ref_pos_segment_model->get_by_id($id);

		if ($row) {
			$data = array('button' => 'Update', 'action' => site_url('ref_pos_segment/update_action'), 'id' => set_value('id', $row->id), 'title' => 'Pos Segment', 'code' => set_value('code', $row->code), 'name' => set_value('name', $row->name), 'short_name' => set_value('short_name', $row->short_name), 'description' => set_value('description', $row->description), 'status' => set_value('status', $row->status), 'is_cover' => set_value('is_cover', $row->is_cover), 'is_captain_order' => set_value('is_captain_order', $row->is_captain_order), 'is_phone_number' => set_value('is_phone_number', $row->is_phone_number), 'is_waiter' => set_value('is_waiter', $row->is_waiter), 'is_autoincrement_tbl_no' => set_value('is_autoincrement_tbl_no', $row->is_autoincrement_tbl_no), 'is_guest_profile' => set_value('is_guest_profile', $row->is_guest_profile), 'is_reservation' => set_value('is_reservation', $row->is_reservation), 'is_rate_code' => set_value('is_rate_code', $row->is_rate_code), 'is_service' => set_value('is_service', $row->is_service), 'is_tax' => set_value('is_tax', $row->is_tax), 'surcharge_percent' => set_value('surcharge_percent', $row->surcharge_percent), 'min_charge' => set_value('min_charge', $row->min_charge), 'hourly_charge' => set_value('hourly_charge', $row->hourly_charge), 'is_food_entry' => set_value('is_food_entry', $row->is_food_entry), 'is_beverage_alcohol' => set_value('is_beverage_alcohol', $row->is_beverage_alcohol), 'is_beverage_non_alcohol' => set_value('is_beverage_non_alcohol', $row->is_beverage_non_alcohol),

			);
			form('referensi/ref_pos_segment/ref_pos_segment_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('referensi/ref_pos_segment'));
		}
	}

	public function update_action() {
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array('code' => $this->input->post('code', TRUE), 'name' => $this->input->post('name', TRUE), 'short_name' => $this->input->post('short_name', TRUE), 'description' => $this->input->post('description', TRUE), 'status' => $this->input->post('status', TRUE), 'is_cover' => $this->input->post('is_cover', TRUE), 'is_captain_order' => $this->input->post('is_captain_order', TRUE), 'is_phone_number' => $this->input->post('is_phone_number', TRUE), 'is_waiter' => $this->input->post('is_waiter', TRUE), 'is_autoincrement_tbl_no' => $this->input->post('is_autoincrement_tbl_no', TRUE), 'is_guest_profile' => $this->input->post('is_guest_profile', TRUE), 'is_reservation' => $this->input->post('is_reservation', TRUE), 'is_rate_code' => $this->input->post('is_rate_code', TRUE), 'is_service' => $this->input->post('is_service', TRUE), 'is_tax' => $this->input->post('is_tax', TRUE), 'surcharge_percent' => $this->input->post('surcharge_percent', TRUE), 'min_charge' => $this->input->post('min_charge', TRUE), 'hourly_charge' => $this->input->post('hourly_charge', TRUE), 'is_food_entry' => $this->input->post('is_food_entry', TRUE), 'is_beverage_alcohol' => $this->input->post('is_beverage_alcohol', TRUE), 'is_beverage_non_alcohol' => $this->input->post('is_beverage_non_alcohol', TRUE),

			);

			$this->Ref_pos_segment_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(base_url('referensi/ref_pos_segment'));
		}
	}

	public function delete($id) {
		$row = $this->Ref_pos_segment_model->get_by_id($id);

		if ($row) {
			$this->Ref_pos_segment_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(base_url('referensi/ref_pos_segment'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(base_url('referensi/ref_pos_segment'));
		}
	}

	public function _rules() {
		$this->form_validation->set_rules('code', 'code', 'trim|required');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('short_name', 'short name', 'trim|required');
		$this->form_validation->set_rules('description', 'description', 'trim|required');
		$this->form_validation->set_rules('status', 'status', 'trim|required');
		/*$this->form_validation->set_rules('is_cover', 'is cover', 'trim|required');
        $this->form_validation->set_rules('is_captain_order', 'is captain order', 'trim|required');
        $this->form_validation->set_rules('is_phone_number', 'is phone number', 'trim|required');
        $this->form_validation->set_rules('is_waiter', 'is waiter', 'trim|required');
        $this->form_validation->set_rules('is_autoincrement_tbl_no', 'is autoincrement tbl no', 'trim|required');
        $this->form_validation->set_rules('is_guest_profile', 'is guest profile', 'trim|required');
        $this->form_validation->set_rules('is_reservation', 'is reservation', 'trim|required');
        $this->form_validation->set_rules('is_rate_code', 'is rate code', 'trim|required');
        $this->form_validation->set_rules('is_service', 'is service', 'trim|required');
        $this->form_validation->set_rules('is_tax', 'is tax', 'trim|required');
        $this->form_validation->set_rules('surcharge_percent', 'surcharge percent', 'trim|required|numeric');
        $this->form_validation->set_rules('min_charge', 'min charge', 'trim|required|numeric');
        $this->form_validation->set_rules('hourly_charge', 'hourly charge', 'trim|required|numeric');
        $this->form_validation->set_rules('is_food_entry', 'is food entry', 'trim|required');
        $this->form_validation->set_rules('is_beverage_alcohol', 'is beverage alcohol', 'trim|required');
        $this->form_validation->set_rules('is_beverage_non_alcohol', 'is beverage non alcohol', 'trim|required');
        $this->form_validation->set_rules('created_date', 'created date', 'trim|required');
        $this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
        $this->form_validation->set_rules('created_by', 'created by', 'trim|required');
        $this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');*/

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

}

/* End of file Ref_pos_segment.php */
/* Location: ./application/controllers/Ref_pos_segment.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 18:40:03 */
/* http://harviacode.com */
