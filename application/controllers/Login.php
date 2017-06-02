<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller {
		public function index() {
			$data['alert'] = "";
			$this->load->view('v_login3', $data);
		}
		function proses() {
			$userid = $this->input->post('userid', true);
			$password = $this->input->post('password', true);
			$outlet = $this->input->post('outlet', true);
			$query = $this->db->query("select * from user where name='" . $userid . "' and password='" . $password . "'");
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$data['role_id'] = $row->role_id;
					$data['name'] = $row->name;
					$data['full_name'] = $row->full_name;
					$data['outlet'] = $outlet;
					$data['is_logged_in'] = true;
				}
				$this->session->set_userdata($data);
				redirect(base_url('main'));
			} else {
				$data['alert'] = "<div class=\"alert alert-danger\">
                                    sorry your username or password is wrong.
                                  </div>";
				$this->load->view('v_login2', $data);
			}
		}
		function logout() {
			session_destroy();
			redirect(base_url());
		}
	}
