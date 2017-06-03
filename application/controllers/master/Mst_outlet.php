<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Mst_outlet extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Mst_outlet_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
			is_logged_in();
		}
		public function index() {
			$data['title'] = 'Outlet';
			datatables('master/mst_outlet/mst_outlet_list', $data);
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->Mst_outlet_model->json();
		}
		public function read($id) {
			$row = $this->Mst_outlet_model->get_by_id($id);
			if ($row) {
				$data = array('id' => $row->id, 'code' => $row->code, 'name' => $row->name,
					'address' => $row->address, 'bill_footer' => $row->bill_footer,
					'status' => $row->status, 'outlet_type_id' => $row->outlet_type_id,
					'cost_center_id' => $row->cost_center_id,
					'delivery_bill_footer' => $row->delivery_bill_footer,
					'no_of_seats' => $row->no_of_seats, 'm2' => $row->m2,
					'last_meal_period' => $row->last_meal_period,
					'curr_meal_period' => $row->curr_meal_period,
					'list_number' => $row->list_number, 'num_of_employee' => $row->num_of_employee,
					'is_allow_cancel_tax' => $row->is_allow_cancel_tax,
					'fo_gl_journal_code' => $row->fo_gl_journal_code,
					'bill_image_uri' => $row->bill_image_uri,
					'bill_image_path' => $row->bill_image_path,
					'small_bill_image_uri' => $row->small_bill_image_uri,
					'small_bill_image_path' => $row->small_bill_image_path,
					'printed_bill_image_uri' => $row->printed_bill_image_uri,
					'printed_bill_image_path' => $row->printed_bill_image_path,
					'small_printed_bill_image_uri' => $row->small_printed_bill_image_uri,
					'small_printed_bill_image_path' => $row->small_printed_bill_image_path,
					'created_date' => $row->created_date, 'modified_date' => $row->modified_date,
					'created_by' => $row->created_by, 'modified_by' => $row->modified_by,);
				$this->load->view('mst_outlet/mst_outlet_read', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('mst_outlet'));
			}
		}
		public function create() {
			$data = array('button' => 'Create', 'title' => 'Outlet',
				'action' => base_url('master/mst_outlet/create_action'), 'id' => set_value('id'),
				'code' => set_value('code'), 'name' => set_value('name'),
				'address' => set_value('address'), 'bill_footer' => set_value('bill_footer'),
				'status' => set_value('status'), 'outlet_type_id' => set_value('outlet_type_id'),
				'cost_center_id' => set_value('cost_center_id'),
				'delivery_bill_footer' => set_value('delivery_bill_footer'),
				'no_of_seats' => set_value('no_of_seats'), 'm2' => set_value('m2'),
				'last_meal_period' => set_value('last_meal_period'),
				'curr_meal_period' => set_value('curr_meal_period'),
				'list_number' => set_value('list_number'),
				'num_of_employee' => set_value('num_of_employee'),
				'is_allow_cancel_tax' => set_value('is_allow_cancel_tax'),
				'fo_gl_journal_code' => set_value('fo_gl_journal_code'),
				'bill_image_uri' => set_value('bill_image_uri'),
				'bill_image_path' => set_value('bill_image_path'),
				'small_bill_image_uri' => set_value('small_bill_image_uri'),
				'small_bill_image_path' => set_value('small_bill_image_path'),
				'printed_bill_image_uri' => set_value('printed_bill_image_uri'),
				'printed_bill_image_path' => set_value('printed_bill_image_path'),
				'small_printed_bill_image_uri' => set_value('small_printed_bill_image_uri'),
				'small_printed_bill_image_path' => set_value('small_printed_bill_image_path'),
			);
			form('master/mst_outlet/mst_outlet_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'address' => $this->input->post('address', true),
					'bill_footer' => $this->input->post('bill_footer', true),
					'status' => $this->input->post('status', true),
					'outlet_type_id' => $this->input->post('outlet_type_id', true),
					'cost_center_id' => $this->input->post('cost_center_id', true),
					'delivery_bill_footer' => $this->input->post('delivery_bill_footer', true),
					'no_of_seats' => $this->input->post('no_of_seats', true),
					'm2' => $this->input->post('m2', true),
					'last_meal_period' => $this->input->post('last_meal_period', true),
					'curr_meal_period' => $this->input->post('curr_meal_period', true),
					'list_number' => $this->input->post('list_number', true),
					'num_of_employee' => $this->input->post('num_of_employee', true),
					'is_allow_cancel_tax' => $this->input->post('is_allow_cancel_tax', true),
					'fo_gl_journal_code' => $this->input->post('fo_gl_journal_code', true),
					'bill_image_uri' => $this->input->post('bill_image_uri', true),
					'bill_image_path' => $this->input->post('bill_image_path', true),
					'small_bill_image_uri' => $this->input->post('small_bill_image_uri', true),
					'small_bill_image_path' => $this->input->post('small_bill_image_path', true),
					'printed_bill_image_uri' => $this->input->post('printed_bill_image_uri', true),
					'printed_bill_image_path' => $this->input->post('printed_bill_image_path',
																	true),
					'small_printed_bill_image_uri' => $this->input->post('small_printed_bill_image_uri',
																		 true),
					'small_printed_bill_image_path' => $this->input->post('small_printed_bill_image_path',
																		  true),);
				$this->Mst_outlet_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(base_url('master/mst_outlet'));
			}
		}
		public function update($id) {
			$row = $this->Mst_outlet_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update', 'title' => 'Outlet',
					'action' => base_url('master/mst_outlet/update_action'),
					'id' => set_value('id', $row->id), 'code' => set_value('code', $row->code),
					'name' => set_value('name', $row->name),
					'address' => set_value('address', $row->address),
					'bill_footer' => set_value('bill_footer', $row->bill_footer),
					'status' => set_value('status', $row->status),
					'outlet_type_id' => set_value('outlet_type_id', $row->outlet_type_id),
					'cost_center_id' => set_value('cost_center_id', $row->cost_center_id),
					'delivery_bill_footer' => set_value('delivery_bill_footer',
														$row->delivery_bill_footer),
					'no_of_seats' => set_value('no_of_seats', $row->no_of_seats),
					'm2' => set_value('m2', $row->m2),
					'last_meal_period' => set_value('last_meal_period', $row->last_meal_period),
					'curr_meal_period' => set_value('curr_meal_period', $row->curr_meal_period),
					'list_number' => set_value('list_number', $row->list_number),
					'num_of_employee' => set_value('num_of_employee', $row->num_of_employee),
					'is_allow_cancel_tax' => set_value('is_allow_cancel_tax',
													   $row->is_allow_cancel_tax),
					'fo_gl_journal_code' => set_value('fo_gl_journal_code',
													  $row->fo_gl_journal_code),
					'bill_image_uri' => set_value('bill_image_uri', $row->bill_image_uri),
					'bill_image_path' => set_value('bill_image_path', $row->bill_image_path),
					'small_bill_image_uri' => set_value('small_bill_image_uri',
														$row->small_bill_image_uri),
					'small_bill_image_path' => set_value('small_bill_image_path',
														 $row->small_bill_image_path),
					'printed_bill_image_uri' => set_value('printed_bill_image_uri',
														  $row->printed_bill_image_uri),
					'printed_bill_image_path' => set_value('printed_bill_image_path',
														   $row->printed_bill_image_path),
					'small_printed_bill_image_uri' => set_value('small_printed_bill_image_uri',
																$row->small_printed_bill_image_uri),
					'small_printed_bill_image_path' => set_value('small_printed_bill_image_path',
																 $row->small_printed_bill_image_path),
				);
				form('master/mst_outlet/mst_outlet_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_outlet'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('code' => $this->input->post('code', true),
					'name' => $this->input->post('name', true),
					'address' => $this->input->post('address', true),
					'bill_footer' => $this->input->post('bill_footer', true),
					'status' => $this->input->post('status', true),
					'outlet_type_id' => $this->input->post('outlet_type_id', true),
					'cost_center_id' => $this->input->post('cost_center_id', true),
					'delivery_bill_footer' => $this->input->post('delivery_bill_footer', true),
					'no_of_seats' => $this->input->post('no_of_seats', true),
					'm2' => $this->input->post('m2', true),
					'last_meal_period' => $this->input->post('last_meal_period', true),
					'curr_meal_period' => $this->input->post('curr_meal_period', true),
					'list_number' => $this->input->post('list_number', true),
					'num_of_employee' => $this->input->post('num_of_employee', true),
					'is_allow_cancel_tax' => $this->input->post('is_allow_cancel_tax', true),
					'fo_gl_journal_code' => $this->input->post('fo_gl_journal_code', true),
					'bill_image_uri' => $this->input->post('bill_image_uri', true),
					'bill_image_path' => $this->input->post('bill_image_path', true),
					'small_bill_image_uri' => $this->input->post('small_bill_image_uri', true),
					'small_bill_image_path' => $this->input->post('small_bill_image_path', true),
					'printed_bill_image_uri' => $this->input->post('printed_bill_image_uri', true),
					'printed_bill_image_path' => $this->input->post('printed_bill_image_path',
																	true),
					'small_printed_bill_image_uri' => $this->input->post('small_printed_bill_image_uri',
																		 true),
					'small_printed_bill_image_path' => $this->input->post('small_printed_bill_image_path',
																		  true),
				);
				$this->Mst_outlet_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(base_url('master/mst_outlet'));
			}
		}
		public function delete($id) {
			$row = $this->Mst_outlet_model->get_by_id($id);
			if ($row) {
				$this->Mst_outlet_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(base_url('master/mst_outlet'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('master/mst_outlet'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('code', 'code', 'trim|required');
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			/*$this->form_validation->set_rules('address', 'address', 'trim|required');
            $this->form_validation->set_rules('bill_footer', 'bill footer', 'trim|required');
            $this->form_validation->set_rules('status', 'status', 'trim|required');
            $this->form_validation->set_rules('outlet_type_id', 'outlet type id', 'trim|required');
            $this->form_validation->set_rules('cost_center_id', 'cost center id', 'trim|required');
            $this->form_validation->set_rules('delivery_bill_footer', 'delivery bill footer', 'trim|required');
            $this->form_validation->set_rules('no_of_seats', 'no of seats', 'trim|required');
            $this->form_validation->set_rules('m2', 'm2', 'trim|required');
            $this->form_validation->set_rules('last_meal_period', 'last meal period', 'trim|required');
            $this->form_validation->set_rules('curr_meal_period', 'curr meal period', 'trim|required');
            $this->form_validation->set_rules('list_number', 'list number', 'trim|required');
            $this->form_validation->set_rules('num_of_employee', 'num of employee', 'trim|required');
            $this->form_validation->set_rules('is_allow_cancel_tax', 'is allow cancel tax', 'trim|required');
            $this->form_validation->set_rules('fo_gl_journal_code', 'fo gl journal code', 'trim|required');
            $this->form_validation->set_rules('bill_image_uri', 'bill image uri', 'trim|required');
            $this->form_validation->set_rules('bill_image_path', 'bill image path', 'trim|required');
            $this->form_validation->set_rules('small_bill_image_uri', 'small bill image uri', 'trim|required');
            $this->form_validation->set_rules('small_bill_image_path', 'small bill image path', 'trim|required');
            $this->form_validation->set_rules('printed_bill_image_uri', 'printed bill image uri', 'trim|required');
            $this->form_validation->set_rules('printed_bill_image_path', 'printed bill image path', 'trim|required');
            $this->form_validation->set_rules('small_printed_bill_image_uri', 'small printed bill image uri', 'trim|required');
            $this->form_validation->set_rules('small_printed_bill_image_path', 'small printed bill image path', 'trim|required');
            $this->form_validation->set_rules('created_date', 'created date', 'trim|required');
            $this->form_validation->set_rules('modified_date', 'modified date', 'trim|required');
            $this->form_validation->set_rules('created_by', 'created by', 'trim|required');
            $this->form_validation->set_rules('modified_by', 'modified by', 'trim|required');
        */
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
	}

	/* End of file Mst_outlet.php */
	/* Location: ./application/controllers/Mst_outlet.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-21 08:55:13 */
	/* http://harviacode.com */
