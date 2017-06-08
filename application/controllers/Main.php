<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Main extends CI_Controller {
		function __construct() {
			parent::__construct();
			is_logged_in();
		}
		function compile($str) {
			return $this->db->query($str)->result();
		}
		public function index() {
			$this->session->unset_userdata('no_bill');
			$this->session->unset_userdata('table');
			$this->session->unset_userdata('order_no');
			$view = "content/main";
			$data = "";
			show($view, $data);
		}
		public function log($el, $stringy = false) {
			if (gettype($el) == 'object' or gettype($el) == 'array' or gettype($el) == 'string') {
				$el = json_encode($el);
			}
			if ($stringy) $el = "JSON.stringify($el)";
			echo '<script language="javascript">console.info(`PHP`, ' . $el . ');</script>';
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
			$user_id = $this->session->userdata('user_id');
			$menuClassId = $this->uri->segment(5);
			$orderId = $this->uri->segment(6);
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
			$data = array(
				"order_id" => $orderId,
				"menu_class_id" => $menuClassId,
				"outlet_menu_id" => $menuId,
				"modifier_id" => null,
				"kitchen_id" => null,
				"serving_status" => 0,
				"order_qty" => 1,
				"price_amount" => $price,
				"total_amount" => $price,
				"created_by" => $user_id,
				"created_date" => date('Y-m-d H:i:s')
			);
			// updating phase #1
			$this->db->set('tax_amount', 'tax_amount+(' . $price . '*tax_percent/100)', false);
			$this->db->where('order_id', $orderId);
			$this->db->update('pos_order_taxes');
			$sum = $this->compile("select sum(tax_amount) tax from pos_order_taxes where order_id=" . $orderId);
			$sum = $sum[0]->tax;
			// updating phase #2
			$this->db->set('sub_total_amount', 'sub_total_amount+' . $price, false);
			$this->db->set('tax_total_amount', $sum, false);
			$this->db->set('due_amount', 'sub_total_amount+tax_total_amount', false);
			$this->db->where('id', $orderId);
			$this->db->update('pos_orders');
			// real inserting
			$this->db->insert('pos_orders_line_item', $data);
			redirect(base_url() . "main/reload_pesan/" . $this->uri->segment(6) . ($this->uri->segment(7) ? "/" . $this->uri->segment(7) : "") . ($this->uri->segment(8) ? "/" . $this->uri->segment(8) : "") . ($this->input->get('search') != "" ? "?search=" . $this->input->get('search') : ""));
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
			if ($this->input->post('select_menu') || $this->input->post('search_food')) {
				redirect(base_url() . "main/payment/" . $this->session->userdata('table') . "/" . $this->input->post('select_menu') . ($this->input->post('select_sub_menu') != "" ? "/" . $this->input->post('select_sub_menu') : "") . ($this->input->post('search_food') != "" ? "?search=" . $this->input->post('search_food') : ""));
			} else {
				redirect(base_url() . "main/payment/" . $this->session->userdata('table') . ($this->uri->segment(4) ? "/" . $this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/" . $this->uri->segment(5) : "") . ($this->input->get('search') != "" ? "?search=" . $this->input->get('search') : ""));
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
			$order_id = $this->uri->segment(3);
			$menu_id = $this->uri->segment(4);
			$price = $this->uri->segment(5);
			$res = $this->db->query("select * from pos_orders_line_item WHERE order_id = " . $order_id . " AND outlet_menu_id = " . $menu_id . " AND serving_status = '0' LIMIT 1");
			$arr = $res->result();
			if (sizeof($arr) > 0) {
				$this->db->where('order_id', $order_id);
				$this->db->where('outlet_menu_id', $menu_id);
				$this->db->where('serving_status', '0');
				$this->db->order_by('id');
				$this->db->limit(1);
				$this->db->delete('pos_orders_line_item');
			} else {
				$this->db->set('serving_status', '4');
				$this->db->where('order_id', $order_id);
				$this->db->where('outlet_menu_id', $menu_id);
				$this->db->where('serving_status', '1');
				$this->db->order_by('id');
				$this->db->limit(1);
				$this->db->update('pos_orders_line_item');
			}
			$this->db->set('tax_amount', 'tax_amount-(' . $price . '*tax_percent/100)', false);
			$this->db->where('order_id', $order_id);
			$this->db->update('pos_order_taxes');
			$sum = $this->compile("select sum(tax_amount) tax from pos_order_taxes where order_id=" . $order_id);
			$sum = $sum[0]->tax;
			// updating phase #2
			$this->db->set('sub_total_amount', 'sub_total_amount-' . $price, false);
			$this->db->set('tax_total_amount', $sum, false);
			$this->db->set('due_amount', 'sub_total_amount+tax_total_amount', false);
			$this->db->where('id', $order_id);
			$this->db->update('pos_orders');
			redirect(base_url() . "main/payment/" . $order_id);
		}
		function input_guest() {
			$user_id = $this->session->userdata('user_id');
			$outlet_id = $this->session->userdata('outlet');
			$table_id = $this->input->post('table');
			$num_of_cover = $this->input->post('guest');
			$count = $this->compile("select count(id)+1 as no_bill from pos_orders where outlet_id='" . $this->session->userdata('outlet') . "'");
			$count = $count[0]->no_bill;
			$code = "CHK-" . $count;
			$this->session->set_userdata('no_bill', $code);
			$this->db->insert('pos_orders', array(
				'code' => $code,
				'outlet_id' => $outlet_id,
				'table_id' => $table_id,
				'num_of_cover' => $num_of_cover,
				'sub_total_amount' => 0,
				'discount_total_amount' => 0,
				'tax_total_amount' => 0,
				'due_amount' => 0,
				'segment_id' => 1,
				'status' => 0,
				'waiter_user_id' => $user_id,
				'created_by' => $user_id,
				'created_date' => date('Y-m-d H:i:s')
			));
			$parentId = $this->db->insert_id();
			$taxes = $this->compile("
				select b.*
				from pos_outlet_tax a
				left join mst_pos_taxes b on b.id = a.pos_tax_id
				where outlet_id  = " . $outlet_id . "
			");
			foreach ($taxes as $tax) {
				$this->db->insert('pos_order_taxes', array(
					'order_id' => $parentId,
					'tax_id' => $tax->id,
					'tax_percent' => $tax->tax_percent,
					'tax_amount' => 0,
					'created_by' => $user_id,
					'created_date' => date('Y-m-d H:i:s')
				));
			}
			redirect(base_url('main/payment/' . $parentId));
		}
		function save_note() {
			$order_id = $this->uri->segment(3);
			$data = $this->input->post();
			$this->db->set('order_notes', $data['note']);
			$this->db->where('id', $order_id);
			$this->db->update('pos_orders');
			//redirect(base_url() . "main/payment/" . $order_id);
		}
		function save_meal_time() {
			redirect(base_url('main'));
		}
		function print_kitchen() {
			$user_id = $this->session->userdata('user_id');
			$order_id = $this->uri->segment(3);
			$status = $this->uri->segment(4);
			$outlet_id = $this->session->userdata('outlet');
			$kitchen=$this->db->query("select now() date,e.name outlet,f.name printer,d.name
				from pos_orders a,pos_orders_line_item b,inv_outlet_menus c,user d,mst_outlet e,mst_kitchen_section f
				where a.id=b.order_id
				and b.outlet_menu_id=c.id
				and a.waiter_user_id=d.id
				and a.outlet_id=e.id
				and b.serving_status=". $status ."
				and c.print_kitchen_section_id=f.id
				and a.id=" . $order_id"
				group by e.name ,f.name");
			$kitchen = $kitchen->result();
			foreach ($kitchen as $row) {
				shell_exec('echo Date : ' . $row->date . ' >' . $row->printer);
				shell_exec('echo Outlet : ' . $row->outlet . ' >' . $row->printer);
				shell_exec('echo Waiter : ' . $row->name . ' >' . $row->printer);
				if($status==0)
					shell_exec('echo Status : New Order >' . $row->printer);
				else
					shell_exec('echo Status : Re-print Order >' . $row->printer);
			}
			$query = $this->db->query("select now() date,e.name outlet,d.name,c.name menu,b.order_qty,f.name printer
				from pos_orders a,pos_orders_line_item b,inv_outlet_menus c,user d,mst_outlet e,mst_kitchen_section f
				where a.id=b.order_id
				and b.outlet_menu_id=c.id
				and a.waiter_user_id=d.id
				and a.outlet_id=e.id
				and b.serving_status=". $status ."
				and c.print_kitchen_section_id=f.id
				and a.id=" . $order_id);
			$i = 1;
			$res = $query->result();
			foreach ($res as $row) {
				shell_exec('echo.  >' . $row->printer);
				shell_exec('echo "' . $row->menu . '	' . $row->order_qty . '">' . $row->printer);
				$i++;
			}
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			shell_exec('echo.  >' . $res[0]->printer);
			$this->db->set('serving_status', '1');
			$this->db->where('order_id', $order_id);
			$this->db->where('serving_status', '0');
			$this->db->update('pos_orders_line_item');
		}
		function open_cash() {
			$order_id = $this->uri->segment(3);
			$message = shell_exec("copy open.txt LPT2");
			echo '<script language="javascript">';
			echo 'alert("Cash draw open");';
			echo 'location.href = "' . base_url() . "main/payment/" . $order_id . '"';
			echo '</script>';
		}
		function print_bill($res, $pay) {
			file_put_contents("bill_".$res.".pdf", fopen(BIRT."&no_bill=".$res."&payment=".$pay."&__dpi=96&__pageoverflow=0&__overwrite=false", 'r'));
			shell_exec('"C:\\Program Files (X86)\Foxit Software\Foxit Reader\Foxit Reader.exe" /t "bill_'.$res.'.pdf"');
			redirect(base_url() . "main");
		}
		function print_split($res, $pay) {
			file_put_contents("split_bill_".$res.".pdf", fopen(BIRT."&no_bill=".$res."&payment=".$pay."&__dpi=96&__pageoverflow=0&__overwrite=false", 'r'));
			shell_exec('"C:\\Program Files (X86)\Foxit Software\Foxit Reader\Foxit Reader.exe" /t "split_bill_'.$res.'.pdf"');
		}
		function merge() {
			$orderId = $this->uri->segment(3);
			$orderId = explode('-', $orderId);
			$i=0;
			foreach ($orderId as $key) {
				if($i>0){
					$newData = array(
						'order_id' => $orderId[0],
						'included_order_id' => $key,
						'created_by' => $this->session->userdata('user_id')
					);
					$ee = $this->db->insert('pos_included_orders', $newData);
				}
				$i++;
			}
			redirect(base_url() . "main/payment/".$this->uri->segment(3));
		}
		function no_pos() {
			$data = $this->input->post();
			$orderId = explode('-', $this->uri->segment(3));
			foreach ($orderId as $key) {
				$this->db->set('order_notes', $data['note']);
				$this->db->set('status', '4', false);
				$this->db->set('modified_by', $this->session->userdata('user_id'), false);
				$this->db->set('modified_date', 'now()', false);
				$this->db->where('id', $key);
				$this->db->update('pos_orders');
			}
			$this->print_bill(implode(',', $orderId), $payment_amount);
		}
		function submit() {
			$P = $this->input->post();
			$user_id = $this->session->userdata('user_id');
			$order_id = isset($P['order_id']) ? $P['order_id'] : '';
			$order_id = explode('-', $order_id);
			$card_no = isset($P['card_no']) ? $P['card_no'] : '';
			$grandtotal = isset($P['grandtotal']) ? $P['grandtotal'] : 0;
			$payment_type_id = isset($P['payment_type_id']) ? $P['payment_type_id'] : NULL;
			$payment_amount = isset($P['payment_amount']) ? $P['payment_amount'] : 0;
			$change_amount = isset($P['change_amount']) ? $P['change_amount'] : 0;
			$folio_id = isset($P['folio_id']) ? $P['folio_id'] : NULL;
			$house_use_id = isset($P['house_use_id']) ? $P['house_use_id'] : NULL;
			$note = isset($P['note']) ? $P['note'] : '';

			if (!$card_no) {
				$payment_amount = str_replace(',', '', $payment_amount);
			} else {
				$payment_amount = $grandtotal;
			}
			$i=0;
			foreach ($order_id as $key) {
				$res = $this->db->query("select * from pos_orders where id=" . $key);
				$res = $res->result();
				if($i==0){
					$newData = array(
						'order_id' => $key,
						'payment_type_id' => $payment_type_id,
						'payment_amount' => $payment_amount,
						'change_amount' => $change_amount,
						'folio_id' => $folio_id,
						'card_no' => $card_no,
						'house_use_Id'=>$house_use_id,
						'total_amount' => floatval($grandtotal),
						'created_by' => $user_id
					);
					$ee = $this->db->insert('pos_payment_detail', $newData);
				}
				$i++;
				$this->db->set('status', '2', false);
				$this->db->set('modified_by', $user_id, false);
				$this->db->set('modified_date', 'now()', false);
				if ($note) {
					$this->db->set('order_notes', $note);
				}
				$this->db->where('id', $key);
				$this->db->update('pos_orders');
			}
			$this->print_bill(implode(',', $order_id), $payment_amount);
		}
		function submit_split() {
			$P = $this->input->post();
			$counter = isset($P['counter']) ? $P['counter'] : '';
			$user_id = $this->session->userdata('user_id');
			$order_id = isset($P['order_id']) ? $P['order_id'] : '';
			$order_id = explode('-', $order_id);
			$id = $order_id[0];
			$card_no = isset($P['card_no']) ? $P['card_no'] : '';
			$grandtotal = isset($P['grandtotal']) ? $P['grandtotal'] : 0;
			$payment_type_id = isset($P['payment_type_id']) ? $P['payment_type_id'] : NULL;
			$payment_amount = isset($P['payment_amount']) ? $P['payment_amount'] : 0;
			$change_amount = isset($P['change_amount']) ? $P['change_amount'] : 0;
			$folio_id = isset($P['folio_id']) ? $P['folio_id'] : NULL;
			$house_use_id = isset($P['house_use_id']) ? $P['house_use_id'] : NULL;
			$note = isset($P['note']) ? $P['note'] : '';
			$newData = array();
			$res = false;
			if (!$card_no) {
				$payment_amount = str_replace(',', '', $payment_amount);
			} else {
				$payment_amount = $grandtotal;
			}
			//
			$i=0;
			foreach($order_id as $id) {
				if ($id && intval($id)) {
					$this->db->set('status', '2', false);
					$this->db->set('modified_by', $user_id, false);
					$this->db->set('modified_date', 'now()', false);
					if ($note) {
						$this->db->set('order_notes', $note);
					}
					$this->db->where('id', $id);
					$this->db->update('pos_orders');
					if (!$i) {
						$newData = array(
							'order_id' => $id,
							'payment_type_id' => $payment_type_id,
							'payment_amount' => floatval($payment_amount),
							'change_amount' => floatval($change_amount),
							'folio_id' => $folio_id,
							'card_no' => $card_no,
							'house_use_id'=> $house_use_id,
							'total_amount' => floatval($grandtotal),
							'created_by' => $user_id
						);
						$res = $this->db->insert('pos_payment_detail', $newData);
					}
				}
				$i++;
			}
			header('Content-Type: application/json');
			$data = array(
				'result' => $res,
				'url' => BIRT .'&no_bill='.  implode('-', $order_id) . '&payment=' . $payment_amount
			);
			echo json_encode($data);
			$this->print_split(implode(',', $order_id), $payment_amount);
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
			$user_id = $this->session->userdata('user_id');
			$menuClassId = $post['menu_class_id'];
			$orderId = $this->uri->segment(3);
			$menuId = $insert_id;
			$outletId = $post['outlet'];
			$price = $post['menu_price'];
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
			$data = array(
				"order_id" => $orderId,
				"menu_class_id" => $menuClassId,
				"outlet_menu_id" => $menuId,
				"modifier_id" => null,
				"kitchen_id" => null,
				"serving_status" => 0,
				"order_qty" => 1,
				"price_amount" => $price,
				"total_amount" => $price,
				"created_by" => $user_id,
				"created_date" => date('Y-m-d H:i:s')
			);
			// updating phase #1
			$this->db->set('tax_amount', 'tax_amount+(' . $price . '*tax_percent/100)', false);
			$this->db->where('order_id', $orderId);
			$this->db->update('pos_order_taxes');
			$sum = $this->compile("select sum(tax_amount) tax from pos_order_taxes where order_id=" . $orderId);
			$sum = $sum[0]->tax;
			// updating phase #2
			$this->db->set('sub_total_amount', 'sub_total_amount+' . $price, false);
			$this->db->set('tax_total_amount', $sum, false);
			$this->db->set('due_amount', 'sub_total_amount+tax_total_amount', false);
			$this->db->where('id', $orderId);
			$this->db->update('pos_orders');
			// real inserting
			$this->db->insert('pos_orders_line_item', $data);
			redirect(base_url() . "main/reload_pesan/" . $this->uri->segment(3) . ($this->uri->segment(4) ? "/" . $this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/" . $this->uri->segment(5) : "") . ($this->input->get('search') != "" ? "?search=" . $this->input->get('search') : ""));
		}
	}
