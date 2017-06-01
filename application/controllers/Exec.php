<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exec extends CI_Controller {
	public function index() {
		$data['alert'] = "";
		$this->load->view('shell', $data);
	}
}
