<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Main extends CI_Controller {
		function __construct() {
			parent::__construct();
			//$this->load->library('datatables');
			is_logged_in();
		}
		function compile($str) {
			return $this->db->query($str)->result();
		}
		public function index() {
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
		function payment() {
			/*$query = $this->db->query("select order_no from pos_outlet_order_header where table_no=".$this->uri->segment(3)." and status_closed=0");
                        if ($query->num_rows() > 0)
                        {
                       foreach ($query->result() as $row)
                            {
                               $order_no= $row->order_no;
                            }
                        }*/
			$data = array('table_no' => $this->uri->segment(3),
				'order_no' => $this->global_model->get_no_bill($this->uri->segment(3)));
			$_SESSION['table'] = $this->uri->segment(3);
			$_SESSION['order_no'] = $this->global_model->get_no_bill($this->uri->segment(3));
			//order_no
			$data['keyword'] = $this->input->post('cari');
			pesan('orders/payment', $data);
		}
		function payment_total() {
			//echo $this->session->userdata('no_bill');exit;
			$data = "";
			pesan('orders/payment_total', $data);
		}
		function order() {
			//echo $tables;exit;
			$data['keyword'] = $this->input->post('cari');
			pesan('orders/pesan', $data);
		}
		function pesan() {
			$query = $this->db->query("select count(*) as tot from pos_outlet_order_header");
			foreach ($query->result() as $row) {
				$tot = $row->tot + 1;
			}
			$data = array('order_no' => $tot, 'table_no' => $this->uri->segment(3),
				'order_no' => $tot);
			$_SESSION['table'] = $this->uri->segment(3);
			$_SESSION['order_no'] = $tot;
			$this->db->insert('pos_outlet_order_header', $data);
			//$_SESSION['item']
			$data = "";
			pesan('orders/pesan', $data);
		}
		function inputpesan() {
			$menuId = $this->uri->segment(3);
			$outletId = $this->session->userdata('outlet');
			$price = $this->uri->segment(4);
			$taxVal = 0;
			$serviceVal = 0;
			$isTaxes = $this->compile("
				select is_tax_included e from inv_outlet_menus where id = $menuId
			");
			if ($isTaxes[0]->e == 'N') {
				$rows = $this->compile('
					select 
						a.outlet_id, 
						max(case when b.code = \'SC\' then tax_percent end) service_charge, 
						max(case when b.code = \'TX\' then tax_percent end) tax
					from pos_outlet_tax a
					left join mst_pos_taxes b on b.id = a.pos_tax_id
					where outlet_id = ' . $outletId . '
					group by a.outlet_id
				');
				$taxVal += $price * ($rows[0]->tax) / 100;
				$serviceVal += $price * ($rows[0]->service_charge) / 100;
				$adTaxes = $this->compile("
					select a.outlet_menu_id, b.name, b.tax_percent 
					from pos_menus_tax a
					left join mst_pos_taxes b on b.id = a.pos_tax_id
					where outlet_menu_id = $menuId
				");
				foreach ($adTaxes as $adTax) {
					if ($adTax->tax_percent > 0) {
						$taxVal += $price * ($adTax->tax_percent) / 100;
					}
				}
			}
			//select * where is_tax_included = 'N'
			$data = array(
				'menu_id' => $this->uri->segment(3),
				'amount' => $this->uri->segment(4),
				'qty' => 1,
				'tax' => $taxVal,
				'service' => $serviceVal,
				'order_no' => $this->session->userdata('order_no'),
				'menu_class_id' => $this->uri->segment(5),
				'table_id' => $this->uri->segment(6),
				'outlet_id' => $outletId
			);
			//order_no
			$this->db->insert('pos_outlet_order_detil', $data);
			// echo $this->db->last_query();exit;
			redirect(base_url() . "main/reload_pesan/" . $this->uri->segment(6) . ($this->uri->segment(7) ? "/".$this->uri->segment(7) : "") . ($this->uri->segment(8) ? "/".$this->uri->segment(8) : "") . ($this->input->get('search') != "" ? "?search=".$this->input->get('search') : ""));
			// echo base_url() . "main/reload_pesan/" . $this->uri->segment(6) . ($this->uri->segment(7) ? "/".$this->uri->segment(7) : "") . ($this->uri->segment(8) ? "/".$this->uri->segment(8) : "") . ($this->input->get('search') != "" ? "?search=".$this->input->get('search') : ""); exit;
			//redirect(base_url()."main/reload_pesan/".$this->session->table);
		}
		function cancel_order() {
			// echo "sss";exit;
			$this->db->set('cancel', '1');
			$this->db->where('outlet_id', $this->session->userdata('outlet'));
			$this->db->where('table_id', $this->session->userdata('table'));
			$this->db->where('order_no', $this->session->userdata('no_bill'));
			$this->db->update('pos_outlet_order_detil');
			redirect(base_url('main'));
		}
		function inputpesan2() {
			$outlet_id = $this->session->userdata('outlet');
			$kode_menu = $this->uri->segment(3);
			$price = $this->uri->segment(4);
			$kode_table = $this->session->userdata('table');
			$segment_id = 2;
			//echo  $price;exit;
			//echo  $this->uri->segment(3);exit;
			//echo $kode_table;exit;
			/*$data = array(
                'code'=>$this->session->userdata('no_bill'),
                'sub_total_amount'=>$price,
                'tax_total_amount'=>11/100*$price,
                'payment_amount'=payment>($price+11/100*$price),
                'transc_batch_id'=>1,
                'meal_time_id'=>1,
                'outlet_id'=> $outlet_id,
                'table_id'=>$kode_table,
                'segment_id'=>$segment_id
            );

            $this->db->insert('pos_orders', $data);
             *
             */
			$data = array('order_id' => $this->session->userdata('no_bill'),
				'outlet_menu_id' => $kode_menu, 'order_qty' => 1,
				'price_amount' => $price, 'total_amount' => $price,);
			$this->db->insert('pos_orders_line_item', $data);
			//echo $this->db->last_query();exit;
			// redirect(base_url()."main/reload_pesan/".$this->uri->segment(4));
			redirect(base_url() . "main/reload_pesan/" . $this->uri->segment(4));
		}
		function reload_pesan() {
			// echo $this->session->userdata('table');exit;
			//echo $this->input->post('select_menu');exit;
			//$data['filter']=$this->input->post('select_menu');
			//$data['keyword']=$this->input->post('select_menu');
			//pesan('orders/pesan',$data);
			if($this->input->post('select_menu') || $this->input->post('search_food')){
				redirect(base_url() . "main/payment/" . $this->session->userdata('table') . "/" . $this->input->post('select_menu') . ($this->input->post('select_sub_menu') != "" ? "/".$this->input->post('select_sub_menu') : "") . ($this->input->post('search_food') != "" ? "?search=".$this->input->post('search_food') : ""));
			}else{
				redirect(base_url() . "main/payment/" . $this->session->userdata('table') . ($this->uri->segment(4) ? "/".$this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/".$this->uri->segment(5) : "") . ($this->input->get('search') != "" ? "?search=".$this->input->get('search') : ""));
			}
		}
		function get_total() {
			$data = "";
			pesan('orders/get_total', $data);
		}
		function cari() {
			$keyword = $this->input->post('cari', true);
			$sql = "select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . " and a.name like '%" . $keyword . "%'";
			//echo $sql;exit;
			$query = $this->db->query($sql);
			$data['query'] = $query->result();
			pesan('orders/search', $data);
			//echo $keyword;exit;
		}
		function void_item() {
			// echo "sss";
			$table_id = $this->uri->segment(3);
			$outlet_id = $this->uri->segment(4);
			$menu_id = $this->uri->segment(5);
			$this->db->set('is_void', '1');
			$this->db->where('outlet_id', $outlet_id);
			$this->db->where('menu_id', $menu_id);
			$this->db->where('table_id', $table_id);
			$this->db->where('is_void', 0);
			$this->db->limit(1);
			$this->db->update('pos_outlet_order_detil');
			redirect(base_url() . "main/payment/" . $this->session->userdata('table'));
		}
		function input_guest() {
			//echo $this->session->userdata('no_bill');exit;
			$outlet_id = $this->session->userdata('outlet');
			$table_id = $this->input->post('table');
			$guest = $this->input->post('guest');
			$tables = $this->uri->segment(3);
			$this->session->set_userdata('table', $tables);
			$query = $this->db->query("select count(id)+1 as no_bill from pos_outlet_order_header where outlet_id='" . $this->session->userdata('outlet') . "'");
			//echo $this->db->last_query();exit;
			foreach ($query->result() as $row) {
				$nomor_bill = "CHK-" . $row->no_bill;
			}
			// echo $nomor_bill;exit;
			$this->session->set_userdata('no_bill', $nomor_bill);
			//echo $this->input->post('no_bill');exit;
			$data = array('outlet_id' => $outlet_id, 'table_no' => $table_id, 'guest' => $guest,
				'status_closed' => 0, 'order_no' => $nomor_bill);
			$this->db->insert('pos_outlet_order_header', $data);
			// echo $table_id;exit;
			redirect(base_url('main/order/' . $table_id));
			// echo $outlet_id;exit;
		}
		function save_note() {
			//$bill=$this->session->userdata('no_bill');
			$outlet_id = $this->session->userdata('outlet');
			$table_id = $this->uri->segment(3);
			// echo  $outlet_id;exit;
			$this->db->set('outlet_id', $outlet_id);
			$this->db->set('table_id', $table_id);
			$this->db->set('note', $this->input->post('note'));
			$this->db->where('closed_bill', 0);
			$this->db->update('mytable');
			redirect(base_url('main/payment/' . $table_id));
		}
		function save_meal_time() {
			redirect(base_url('main'));
		}
		function print_kitchen() {
			echo "test";
			printer_open();
		}
		function include_room() {
			$no_bill = $this->input->post('bill');
			$outlet = $this->input->post('outlet');
			$table = $this->input->post('table');
			$room = $this->input->post('room');
			$query = $this->db->query("select SUM(amount) as amount, SUM(tax) as tax from pos_outlet_order_detil where table_id='" . $table . "' and outlet_id='" . $outlet . "' and order_no='" . $no_bill . "'");
			foreach ($query->result() as $row) {
				$amount = $row->amount;
				$tax = $row->tax;
			}
			$data = array('total' => $amount, 'tax' => $tax, 'folio_id' => $room,
				'status_closed' => 1);
			$this->db->where('order_no', $no_bill);
			$this->db->where('table_no', $table);
			$this->db->where('outlet_id', $outlet);
			$this->db->update('pos_outlet_order_header', $data);
			$data = array('closed_bill' => 1);
			$this->db->where('order_no', $no_bill);
			$this->db->where('table_id', $table);
			$this->db->where('outlet_id', $outlet);
			$this->db->update('pos_outlet_order_detil', $data);
			redirect(base_url() . "main/reload_pesan/" . $table);
		}
		function payment_update() {
			$no_bill = $this->uri->segment(3);
			$table = $this->uri->segment(4);
			$outlet = $this->uri->segment(5);
			//  $room=$this->input->post('room');
			$query = $this->db->query("select SUM(amount) as amount, SUM(tax) as tax from pos_outlet_order_detil where table_id='" . $table . "' and outlet_id='" . $outlet . "' and order_no='" . $no_bill . "'");
			foreach ($query->result() as $row) {
				$amount = $row->amount;
				$tax = $row->tax;
			}
			$data = array('total' => $amount, 'tax' => $tax, 'folio_id' => 0, 'status_closed' => 1);
			$this->db->where('order_no', $no_bill);
			$this->db->where('table_no', $table);
			$this->db->where('outlet_id', $outlet);
			$this->db->update('pos_outlet_order_header', $data);
			$data = array('closed_bill' => 1);
			$this->db->where('order_no', $no_bill);
			$this->db->where('table_id', $table);
			$this->db->where('outlet_id', $outlet);
			$this->db->update('pos_outlet_order_detil', $data);
		}

		function openmenu() {
			$this->load->model('Inv_outlet_menus_model');
			$inv_outlet_menus = new Inv_outlet_menus_model();
			$inv_outlet_menus->getNextCode();

			$post = $this->input->post();
			// echo "<pre>";
			// print_r($post);
			// exit;

			$data = array();
			$data['outlet_id'] = $post['outlet'];
			$data['menu_class_id'] = $post['menu_class_id'];
			$data['menu_group_id'] = $post['menu_group_id'];
			$data['meal_time_id'] = $post['meal_time_id'];
			$data['menu_type'] = 'O';
			$data['code'] = $inv_outlet_menus->getNextCode();
			$data['name'] = $post['menu_name'];
			$data['short_name'] = $post['menu_name'];
			$data['description'] = "";
			$data['menu_price'] = $post['menu_price'];

			$this->db->insert('inv_outlet_menus', $data);
			$insert_id = $this->db->insert_id();
			$row = $inv_outlet_menus->get_by_id($insert_id);

			$menuId = $row->id;
			$outletId = $row->outlet_id;
			$price = $row->menu_price;
			$taxVal = 0;
			$serviceVal = 0;
			$isTaxes = $this->compile("
				select is_tax_included e from inv_outlet_menus where id = $menuId
			");
			if ($isTaxes[0]->e == 'N') {
				$rows = $this->compile('
					select 
						a.outlet_id, 
						max(case when b.code = \'SC\' then tax_percent end) service_charge, 
						max(case when b.code = \'TX\' then tax_percent end) tax
					from pos_outlet_tax a
					left join mst_pos_taxes b on b.id = a.pos_tax_id
					where outlet_id = ' . $outletId . '
					group by a.outlet_id
				');
				$taxVal += $price * ($rows[0]->tax) / 100;
				$serviceVal += $price * ($rows[0]->service_charge) / 100;
				$adTaxes = $this->compile("
					select a.outlet_menu_id, b.name, b.tax_percent 
					from pos_menus_tax a
					left join mst_pos_taxes b on b.id = a.pos_tax_id
					where outlet_menu_id = $menuId
				");
				foreach ($adTaxes as $adTax) {
					if ($adTax->tax_percent > 0) {
						$taxVal += $price * ($adTax->tax_percent) / 100;
					}
				}
			}
			//select * where is_tax_included = 'N'
			$data = array(
				'menu_id' => $row->id,
				'amount' => $post['menu_price'],
				'qty' => $post['menu_qty'],
				'tax' => $taxVal,
				'service' => $serviceVal,
				'order_no' => $this->session->userdata('order_no'),
				'menu_class_id' => $post['menu_class_id'],
				'table_id' => $post['table'],
				'outlet_id' => $post['outlet']
			);
			//order_no
			$this->db->insert('pos_outlet_order_detil', $data);

			redirect(base_url() . "main/reload_pesan/" . $this->uri->segment(3) . ($this->uri->segment(4) ? "/".$this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/".$this->uri->segment(5) : "") . ($this->input->get('search') != "" ? "?search=".$this->input->get('search') : ""));
		}
	}
