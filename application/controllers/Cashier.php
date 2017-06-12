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
	}
