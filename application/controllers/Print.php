<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print extends CI_Controller {
	function __construct()
    {
        parent::__construct();

	//$this->load->library('datatables');
        is_logged_in();
    }
	public function index()
	{
		$this->session->unset_userdata('no_bill');
        $this->session->unset_userdata('table');
         $this->session->unset_userdata('order_no');
        //echo $this->session->userdata('outlet');exit;
        //echo $this->session->userdata('outlet');exit;
		//$this->load->view('main/v_main');
                    $view = "content/main";
                    $data = "";

            show($view, $data);
	}
}
