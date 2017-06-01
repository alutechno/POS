<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_list extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Ref_outlet_type_model');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		is_logged_in();
	}

	public function index() {
		$data['title'] = "Store";
		report('report/report_list', $data);
	}

	function show() {
		$string = $this->input->post('period');
		$date = explode('-', $string);

		echo "<script>
            var url='" . REPORT_BIRT . $this->input->post('reportid') . ".rptdesign&date1=" . trim($date[0]) . "&date2=" . trim($date[1]) . "';
          //alert(url);
            window.open(url,'_blank');
            </script>";
		//redirect(base_url('report/report_list'));
		$data['title'] = "Store";
		report('report/report_list', $data);
	}


}

/* End of file Ref_outlet_type.php */
/* Location: ./application/controllers/Ref_outlet_type.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-04-07 08:37:15 */
/* http://harviacode.com */
