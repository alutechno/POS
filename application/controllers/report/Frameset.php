<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frameset extends CI_Controller {
	function __construct() {
		parent::__construct();
		is_logged_in();
	}

	public function index() {
		$data['alert'] = "";
		$query = $this->db->query("select * from ref_outlet_menu_class");

		$rows = "";
		foreach ($query->result() as $row) {
			$rows[] = $row;
		}
		$data['rows'] = $rows;

		$message=shell_exec("ls");
		echo 'Command result<pre>';
		echo $message;
		echo '<pre>========================<br>';
		$this->load->view('report/print_order', $data);

	}


}

