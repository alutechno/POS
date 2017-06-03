<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class User extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('User_model');
			$this->load->library('form_validation');
			$this->load->library('datatables');
			is_logged_in();
		}
		public function index() {
			$data['title'] = "User";
			datatables('setting/user/user_list', $data);
		}
		public function json() {
			header('Content-Type: application/json');
			echo $this->User_model->json();
		}
		public function read($id) {
			$row = $this->User_model->get_by_id($id);
			if ($row) {
				$data = array('id' => $row->id, 'name' => $row->name, 'password' => $row->password,
					'token' => $row->token, 'role_id' => $row->role_id,
					'full_name' => $row->full_name, 'default_module' => $row->default_module,
					'default_menu' => $row->default_menu, 'mobile' => $row->mobile,
					'email' => $row->email, 'image' => $row->image,
					'department_id' => $row->department_id,);
				$this->load->view('user/user_read', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('user'));
			}
		}
		public function create() {
			$data = array('button' => 'Create', 'action' => base_url('user/create_action'),
				'id' => set_value('id'), 'name' => set_value('name'),
				'password' => set_value('password'), 'token' => set_value('token'),
				'role_id' => set_value('role_id'), 'full_name' => set_value('full_name'),
				'default_module' => set_value('default_module'),
				'default_menu' => set_value('default_menu'), 'mobile' => set_value('mobile'),
				'email' => set_value('email'), 'image' => set_value('image'),
				'department_id' => set_value('department_id'),);
			$this->load->view('user/user_form', $data);
		}
		public function create_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->create();
			} else {
				$data = array('name' => $this->input->post('name', true),
					'password' => $this->input->post('password', true),
					'token' => $this->input->post('token', true),
					'role_id' => $this->input->post('role_id', true),
					'full_name' => $this->input->post('full_name', true),
					'default_module' => $this->input->post('default_module', true),
					'default_menu' => $this->input->post('default_menu', true),
					'mobile' => $this->input->post('mobile', true),
					'email' => $this->input->post('email', true),
					'image' => $this->input->post('image', true),
					'department_id' => $this->input->post('department_id', true),);
				$this->User_model->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(base_url('user'));
			}
		}
		public function update($id) {
			$row = $this->User_model->get_by_id($id);
			if ($row) {
				$data = array('button' => 'Update', 'action' => base_url('user/update_action'),
					'id' => set_value('id', $row->id), 'name' => set_value('name', $row->name),
					'password' => set_value('password', $row->password),
					'token' => set_value('token', $row->token),
					'role_id' => set_value('role_id', $row->role_id),
					'full_name' => set_value('full_name', $row->full_name),
					'default_module' => set_value('default_module', $row->default_module),
					'default_menu' => set_value('default_menu', $row->default_menu),
					'mobile' => set_value('mobile', $row->mobile),
					'email' => set_value('email', $row->email),
					'image' => set_value('image', $row->image),
					'department_id' => set_value('department_id', $row->department_id),);
				$this->load->view('user/user_form', $data);
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('user'));
			}
		}
		public function update_action() {
			$this->_rules();
			if ($this->form_validation->run() == false) {
				$this->update($this->input->post('id', true));
			} else {
				$data = array('name' => $this->input->post('name', true),
					'password' => $this->input->post('password', true),
					'token' => $this->input->post('token', true),
					'role_id' => $this->input->post('role_id', true),
					'full_name' => $this->input->post('full_name', true),
					'default_module' => $this->input->post('default_module', true),
					'default_menu' => $this->input->post('default_menu', true),
					'mobile' => $this->input->post('mobile', true),
					'email' => $this->input->post('email', true),
					'image' => $this->input->post('image', true),
					'department_id' => $this->input->post('department_id', true),);
				$this->User_model->update($this->input->post('id', true), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(base_url('user'));
			}
		}
		public function delete($id) {
			$row = $this->User_model->get_by_id($id);
			if ($row) {
				$this->User_model->delete($id);
				$this->session->set_flashdata('message', 'Delete Record Success');
				redirect(base_url('user'));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(base_url('user'));
			}
		}
		public function _rules() {
			$this->form_validation->set_rules('name', 'name', 'trim|required');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			$this->form_validation->set_rules('token', 'token', 'trim|required');
			$this->form_validation->set_rules('role_id', 'role id', 'trim|required');
			$this->form_validation->set_rules('full_name', 'full name', 'trim|required');
			$this->form_validation->set_rules('default_module', 'default module', 'trim|required');
			$this->form_validation->set_rules('default_menu', 'default menu', 'trim|required');
			$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
			$this->form_validation->set_rules('email', 'email', 'trim|required');
			$this->form_validation->set_rules('image', 'image', 'trim|required');
			$this->form_validation->set_rules('department_id', 'department id', 'trim|required');
			$this->form_validation->set_rules('id', 'id', 'trim');
			$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		}
		public function excel() {
			$this->load->helper('exportexcel');
			$namaFile = "user.xls";
			$judul = "user";
			$tablehead = 0;
			$tablebody = 1;
			$nourut = 1;
			//penulisan header
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment;filename=" . $namaFile . "");
			header("Content-Transfer-Encoding: binary ");
			xlsBOF();
			$kolomhead = 0;
			xlsWriteLabel($tablehead, $kolomhead++, "No");
			xlsWriteLabel($tablehead, $kolomhead++, "Name");
			xlsWriteLabel($tablehead, $kolomhead++, "Password");
			xlsWriteLabel($tablehead, $kolomhead++, "Token");
			xlsWriteLabel($tablehead, $kolomhead++, "Role Id");
			xlsWriteLabel($tablehead, $kolomhead++, "Full Name");
			xlsWriteLabel($tablehead, $kolomhead++, "Default Module");
			xlsWriteLabel($tablehead, $kolomhead++, "Default Menu");
			xlsWriteLabel($tablehead, $kolomhead++, "Mobile");
			xlsWriteLabel($tablehead, $kolomhead++, "Email");
			xlsWriteLabel($tablehead, $kolomhead++, "Image");
			xlsWriteLabel($tablehead, $kolomhead++, "Department Id");
			foreach ($this->User_model->get_all() as $data) {
				$kolombody = 0;
				//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
				xlsWriteNumber($tablebody, $kolombody++, $nourut);
				xlsWriteLabel($tablebody, $kolombody++, $data->name);
				xlsWriteLabel($tablebody, $kolombody++, $data->password);
				xlsWriteLabel($tablebody, $kolombody++, $data->token);
				xlsWriteNumber($tablebody, $kolombody++, $data->role_id);
				xlsWriteLabel($tablebody, $kolombody++, $data->full_name);
				xlsWriteNumber($tablebody, $kolombody++, $data->default_module);
				xlsWriteNumber($tablebody, $kolombody++, $data->default_menu);
				xlsWriteLabel($tablebody, $kolombody++, $data->mobile);
				xlsWriteLabel($tablebody, $kolombody++, $data->email);
				xlsWriteLabel($tablebody, $kolombody++, $data->image);
				xlsWriteNumber($tablebody, $kolombody++, $data->department_id);
				$tablebody++;
				$nourut++;
			}
			xlsEOF();
			exit();
		}
	}

	/* End of file User.php */
	/* Location: ./application/controllers/User.php */
	/* Please DO NOT modify this information : */
	/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 03:55:43 */
	/* http://harviacode.com */
