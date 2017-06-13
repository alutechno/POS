<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Cashier extends CI_Controller {
		function __construct() {
			parent::__construct();
			is_logged_in();
		}
		public function index() {
			$view = "content/cashier";
			$data = "";
			show($view, $data);
		}
		function close_cashier () {
			$date = date('Y-m-d H:i:s');
			$sess = $this->session->userdata();
			$shift = $sess['shift'];
			$this->db->set('end_time', $date);
			$this->db->set('modified_by', $shift->id);
			$this->db->set('modified_date', $sess['user_id']);
			$this->db->where('id', $shift->id);
			$this->db->update('pos_cashier_transaction');
			redirect(base_url('main'));
		}
	}
