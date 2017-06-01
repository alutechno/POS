<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_Controller {


	function __construct() {
		parent::__construct();
		$this->load->library("Pdf");
	}

	public function index() {
		$data = "";
		//$this->load->view('v_login2',$data);
		tables('pos/pos_tables', $data);
	}

	function pesan() {


		$query = $this->db->query("select count(*) as tot from pos_outlet_order_header");

		foreach ($query->result() as $row) {
			$tot = $row->tot + 1;

		}

		$data = array('order_no' => $tot, 'table_no' => $this->uri->segment(4), 'order_no' => $tot);

		$_SESSION['table'] = $this->uri->segment(4);
		$_SESSION['order_no'] = $tot;

		$this->db->insert('pos_outlet_order_header', $data);

		//$_SESSION['item']
		$data = "";
		pesan('pos/pesan', $data);
	}

	function inputpesan() {
		//echo  $this->uri->segment(5);exit;

		$data = array('menu_id' => $this->uri->segment(5), 'amount' => 30000, 'qty' => 1, 'tax' => 30000 / 10, 'order_no' => $this->session->order_no);

		$this->db->insert('pos_outlet_order_detil', $data);


		//echo $this->db->last_query();exit;
		redirect(base_url() . "pos/tables/reload_pesan/" . $this->uri->segment(4));

	}

	function reload_pesan() {
		//echo $this->session->table;exit;
		$data = "";
		pesan('pos/pesan', $data);
	}


	function get_total() {
		$data = "";
		pesan('pos/get_total', $data);
	}

	function get_total_print() {
		$data = "";
		pesan('pos/get_total_print', $data);
	}


	public function create_pdf() {
		//============================================================+
		// File name   : example_001.php
		//
		// Description : Example 001 for TCPDF class
		//               Default Header and Footer
		//
		// Author: Muhammad Saqlain Arif
		//
		// (c) Copyright:
		//               Muhammad Saqlain Arif
		//               PHP Latest Tutorials
		//               http://www.phplatesttutorials.com/
		//               saqlain.sial@gmail.com
		//============================================================+


		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Muhammad Saqlain Arif');
		$pdf->SetTitle('TCPDF Example 001');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------    

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 14, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

		// Set some content to print
		$html = <<<EOD
    <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
    <i>This is the first example of TCPDF library.</i>
    <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
    <p>Please check the source code documentation and other examples for further information.</p>
     
EOD;

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// ---------------------------------------------------------    

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_001.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+
	}

}
