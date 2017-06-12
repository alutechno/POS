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
					$data['user_id'] = $row->id;
					$data['role_id'] = $row->role_id;
					$data['name'] = $row->name;
					$data['full_name'] = $row->full_name;
					$data['outlet'] = $outlet;
					$data['is_logged_in'] = true;
				}
				$shift = $this->db->query("
					select 
						a.id, a.code, a.user_id, a.working_shift_id, 
						a.outlet_id, a.start_time, a.end_time, a.begin_saldo,
						a.closing_saldo, b.name shift_name, c.name outlet_name,
						b.start_time should_start_time, b.end_time should_end_time
					from pos_cashier_transaction a
					left join ref_pos_working_shift b on a.working_shift_id = b.id
					left join mst_outlet c on a.outlet_id = c.id
					where a.user_id=$row->id and a.outlet_id=$outlet and (a.end_time is null or a.end_time = 0);					
				");
				if ($shift->num_rows() == 0) {
					$this->session->set_userdata($data);
					echo "<script>";
					echo "var saldo = prompt('Please enter your begining saldo', 0);";
					echo "saldo = parseFloat(saldo);";
					echo "if (saldo && saldo > 0) {";
					echo "    window.location.href = '" . base_url() . "login/continue_process/' + saldo";
					echo "}";
					echo "</script>";
				} else {
					$data['shift'] = $shift->result()[0];
					$this->session->set_userdata($data);
					redirect(base_url('main'));
				}
			} else {
				$data['alert'] = "<div class=\"alert alert-danger\">sorry your username or password is wrong.</div>";
				$this->load->view('v_login2', $data);
			}
		}
		function continue_process() {
			$code = $this->db->query("select concat('PCS/', curr_item_code('', DATE_FORMAT(CURRENT_DATE, '%Y%m%d'))) id;");
			$code = $code->result();
			$working = $this->db->query("select id, name, start_time, end_time from ref_pos_working_shift where start_time >= CURRENT_TIME and end_time < CURRENT_TIME;");
			$working = $working->result();
			$data = array(
				"code" => $code[0]->id,
				"user_id" => $this->session->userdata('user_id'),
				"working_shift_id" => $working[0]->id,
				"outlet_id" => $this->session->userdata('outlet'),
				"start_time" => date('Y-m-d H:i:s'),
				"created_date" => date('Y-m-d H:i:s'),
				"end_time" => null,
				"begin_saldo" => $this->uri->segment(3),
				"closing_saldo" => 0,
				"created_by" => $this->session->userdata('user_id')
			);
			$this->db->insert('pos_cashier_transaction', $data);
			$parentId = $this->db->insert_id();
			//
			$shift = $this->db->query("
				select 
					a.id, a.code, a.user_id, a.working_shift_id, 
					a.outlet_id, a.start_time, a.end_time, a.begin_saldo,
					a.closing_saldo, b.name shift_name, c.name outlet_name,
					b.start_time should_start_time, b.end_time should_end_time
				from pos_cashier_transaction a
				left join ref_pos_working_shift b on a.working_shift_id = b.id
				left join mst_outlet c on a.outlet_id = c.id
				where a.id=$parentId;					
			");
			$data['shift'] = $shift->result()[0];
			$this->session->set_userdata($data);
			redirect(base_url('main'));
		}
		function logout() {
			session_destroy();
			redirect(base_url());
		}
	}
