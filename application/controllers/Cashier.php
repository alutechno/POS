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
		function print_cashier_report ($id) {
			file_put_contents("bills\cashier_report_".$id.".pdf", fopen(BIRT_CLOSE_CASHIER.$id, 'r'));
			shell_exec('"C:\Program Files (X86)\Foxit Software\Foxit Reader\Foxit Reader.exe" /t "bills\cashier_report_'.$id.'.pdf"');
			redirect(base_url() . "main");
		}
		function close_cashier () {
			$date = date('Y-m-d H:i:s');
			$sess = $this->session->userdata();
			$shift = $sess['shift'];
			$closingSaldo = $this->db->query("
				select
					#a.id, a.code, a.transc_batch_id, c.payment_amount, c.change_amount,
					sum(c.payment_amount-c.change_amount) uang
				from pos_orders a
				join pos_cashier_transaction b on b.id = a.transc_batch_id
				join pos_payment_detail c on c.order_id = a.id
				where c.payment_type_id in (select id from ref_payment_method where name like '%cash%')
				and b.id = $shift->id
			")->result();
			$this->db->set('end_time', $date);
			$this->db->set('closing_saldo', $closingSaldo[0]->uang);
			$this->db->set('modified_by', $sess['user_id']);
			$this->db->set('modified_date', $date);
			$this->db->where('id', $shift->id);
			$this->db->update('pos_cashier_transaction');
			$this->print_cashier_report($shift->id);
			redirect(base_url('main'));
		}
	}
