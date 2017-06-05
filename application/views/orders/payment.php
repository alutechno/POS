<div class="modal modal-sm fade" id="myModalCash" tabindex="-1" role="dialog"
	 aria-labelledby="myModalCash-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalCash-label">Cash Payment</h4>
			</div>
			<form id="subscribe-email-form" action="<?php echo base_url(); ?>main/submit"
				  method="post">
				<div class="modal-body">
					<?php
						$orderId = $this->uri->segment(3);
						$orderId = explode('-', $orderId);
						$orderId = implode(",", $orderId);
						$q = $this->db->query("
							select
							c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
							from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
							sum(sub_total_amount) total
							from pos_orders
							where id in(" . $orderId . ")) d
							where b.tax_id=c.id
							and b.order_id in(" . $orderId . ")
							group by c.name;"
						);
						$rows = $q->result();
						function html($label, $val, $id = '') {
							$val = floatval($val);
							if (!($val > 0)) $val = 0;

							return '
								<div class="row">
									<div class="col-lg-6">
										<label>' . $label . '</label>
									</div>
									<div class="col-lg-6 text-right">
										<label style="margin-right: 13px;" for="' . $id . '" val="' . $val . '">
										' . rupiah($val, 2) . '</label>
									</div>
								</div>';
						}

						echo html('Total', $rows[0]->total);
						foreach ($rows as $row) {
							$l = '&nbsp&nbsp' . $row->name . '<small>&nbsp&nbsp&nbsp' .
								rupiah($row->tax_percent, 2) .
								'% </small>';
							$v = $row->tax_amount;
							echo html($l, $v);
						}
						echo html('Grand Total', $rows[0]->grandtotal, 'grandtot');
					?>
					<div class="row">
						<div class="col-lg-6">
							<label for="usr">Cash with</label>
						</div>
						<div class="col-lg-6 text-right">
							<input type="hidden" class="form-control" name="payment_type_id"
								   value="11">
							<input type="hidden" class="form-control" name="grandtotal"
								   value="<?php echo $rows[0]->grandtotal; ?>">
							<input type="hidden" class="form-control" name="order_id"
								   value="<?php echo $orderId; ?>">
							<input type="currency" class="form-control" name="payment_amount">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label for="usr">Change</label>
						</div>
						<div class="col-lg-6 text-right">
							<label for="change" style="margin-right: 13px;"></label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close
					</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalcard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Debit/Credit Card Payment</h4>
			</div>
			<form id="subscribe-email-form" action="<?php echo base_url(); ?>main/submit"
				  method="post">
				<div class="modal-body">
					<?php
						$orderId = $this->uri->segment(3);
						$orderId = explode('-', $orderId);
						$orderId = implode(",", $orderId);
						$q = $this->db->query("
								select
								c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
								from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
								sum(sub_total_amount) total
								from pos_orders
								where id in(" . $orderId . ")) d
								where b.tax_id=c.id
								and b.order_id in(" . $orderId . ")
								group by c.name;"
						);
						$rows = $q->result();
						echo html('Total', $rows[0]->total);
						foreach ($rows as $row) {
							$l = '&nbsp&nbsp' . $row->name . '<small>&nbsp&nbsp&nbsp' . rupiah($row->tax_percent,
																							   2) . '% </small>';
							$v = $row->tax_amount;
							echo html($l, $v);
						}
						echo html('Grand Total', $rows[0]->grandtotal, 'grandtot');
					?>
					<hr/>
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label>Type</label>
								<select class="form-control" id="cc_type">
									<option value="credit">Credit</option>
									<option value="debit">Debit</option>
								</select>
							</div>
						</div>
						<div class="col-lg-9">
							<div class="form-group">
								<label>Bank</label>
								<select class="form-control" name="payment_type_id">
									<?php
										$query = $this->db->query("select id, code, name, description from ref_payment_method where category = 'CC' and status = '1'");
										foreach ($query->result() as $row) {
											?>
											<option value="<?php echo $row->id ?>">
												<?php echo $row->code . ' - ' . $row->name ?>
											</option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<hr/>
							<input type="hidden" class="form-control" name="order_id"
								   value="<?php echo $orderId; ?>">
							<input type="hidden" class="form-control" name="grandtotal"
								   value="<?php echo $rows[0]->grandtotal; ?>">
							<input id="card_swiper" type="force-text" class="form-control"
								   id="card_no"
								   name="card_no" placeholder="Tap here, then swipe the card">
							<hr/>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-7">
							<div class="form-group">
								<label for="usr">Number:</label>
								<input id="card_no" type="text" class="form-control" id="card_no"
									   name="card_no">
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group">
								<label for="usr">Name:</label>
								<input id="card_name" type="text" class="form-control"
									   name="cust_name">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close
					</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalMerge" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Choose Tables</h4>
			</div>
			<form id="mergeTable">
				<div class="modal-body">
					<?php
						$orderId = $this->uri->segment(3);
						$orderId = explode('-', $orderId);
						$orderId = implode(",", $orderId);
						$q = $this->db->query("
							select
							c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
							from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
							sum(sub_total_amount) total
							from pos_orders
							where id in(" . $orderId . ")) d
							where b.tax_id=c.id
							and b.order_id in(" . $orderId . ")
							group by c.name;"
						);
						$rows = $q->result();
						echo html('This Table', $rows[0]->grandtotal, 'grantotal-merge-me');
					?>
					<br/>
					<div class="row">
						<style>
							.state-icon {
								left: -5px;
							}
							.list-group-item-primary {
								color: rgb(255, 255, 255);
								background-color: rgb(66, 139, 202);
							}
						</style>
						<div class="col-lg-12">
							<div style="max-height: 500px;overflow: auto;">
								<ul id="check-list-box" class="list-group checked-list-box">
									<?php
										$query = $this->db->query("
											select * from (
												select
													a.id,a.table_no,a.cover, b.id order_id, b.num_of_cover guest,
													b.sub_total_amount, b.discount_total_amount, b.tax_total_amount, 
													b.payment_amount, b.due_amount
												from mst_pos_tables a
												left join pos_orders b on a.id=b.table_id and b.status in (0,1)
												where a.outlet_id=". $this->session->userdata('outlet') ."
												group by a.id order by b.id DESC
											) x 
											where order_id is not null and order_id != ". $this->session->userdata('table') ."
											order by table_no, id;
										");
										foreach ($query->result() as $row) {
											$oid = $row->order_id;
											$tno = $row->table_no;
											$due = $row->due_amount;
											$attr = ' id="merge-w-'.$oid.'" class="list-group-item" data-color="info"';
											$badge = "<span class='badge badge-default badge-pill'>".
												rupiah($due,2)."</span>";
											echo "<li".$attr.">Table #".$tno.$badge."</li>";
											echo "<script>
												window.mergeWith = window.mergeWith || {};
												mergeWith['merge-w-$oid'] = ".json_encode($row)."
											</script>";
										}
									?>
								</ul>
							</div>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-6">
									<label>Grand Total</label>
								</div>
								<div class="col-lg-6 pull-right">
									<span id="grantotal-merge"
										  order_id="<?php echo $this->session->userdata('table'); ?>"
										  class="pull-right"
										  style="margin-right: 20px;">
										<?php echo rupiah($rows[0]->grandtotal,2); ?>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="mergeTableBtn" disabled>
						Submit
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalOpenMenu" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Open Menu</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form"
					  action="<?php echo base_url() ?>main/openmenu<?php echo ($this->uri->segment(3) ? "/" . $this->uri->segment(3) : "") . ($this->uri->segment(4) ? "/" . $this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/" . $this->uri->segment(5) : "") . ($this->input->get('search') ? "?search=" . $this->input->get('search') : "") ?>"
					  method="post">
					<input type="hidden" name="bill"
						   value="<?php echo $this->global_model->get_no_bill($this->uri->segment(3)) ?>">
					<input type="hidden" name="outlet"
						   value="<?php echo $this->session->userdata('outlet') ?>">
					<input type="hidden" name="table" value="<?php echo $this->uri->segment(3) ?>">

					<table class="table table-striped">
						<thead>
						<tr>
							<th>Meal Time</th>
							<th>Menu Type</th>
							<th>Menu Group</th>
							<th>Menu Name</th>
							<th>Qty</th>
							<th>Price</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<select class="form-control" name="meal_time_id" id="meal_time_id">
									<?php
										$query = $this->db->query("select * from ref_meal_time");
										foreach ($query->result() as $row) {
											?>
											<option
												value="<?php echo $row->id ?>" <?php echo $row->id == $this->global_model->get_meal_time() ? 'selected' : ''; ?>><?php echo $row->name ?></option>
											<?php
										}
									?>
								</select>
							</td>
							<td>
								<select class="form-control" name="menu_class_id"
										id="menu_class_id">
									<?php
										$query = $this->db->query("select * from ref_outlet_menu_class");
										foreach ($query->result() as $row) {
											?>
											<option
												value="<?php echo $row->id ?>" <?php echo $row->id == $this->uri->segment(4) ? 'selected' : ''; ?>><?php echo $row->name ?></option>
											<?php
										}
									?>
								</select>
							</td>
							<td>
								<select class="form-control" name="menu_group_id"
										id="menu_group_id">
									<?php
										$query = $this->db->query("select * from  ref_outlet_menu_group");
										foreach ($query->result() as $row) {
											?>
											<option
												value="<?php echo $row->id ?>" <?php echo $row->id == $this->uri->segment(5) ? 'selected' : ''; ?>><?php echo $row->name ?></option>
											<?php
										}
									?>
								</select>
							</td>
							<td><input type="text" class="form-control" name="menu_name"
									   id="menu_name"/></td>
							<td><input type="text" class="form-control" name="menu_qty"
									   id="menu_qty" value="1"/></td>
							<td><input type="text" class="form-control" name="menu_price"
									   id="menu_price"/></td>
						</tr>
						</tbody>
					</table>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close
						</button>
						<button type="submit" class="btn btn-primary">Add Menu</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalnote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Menu Note</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form"
					  action="<?php echo base_url() ?>main/save_note/<?php echo $this->uri->segment(3) ?>"
					  method="post">
					<?php
						$query = $this->db->query("select order_notes from pos_orders where id=" . $this->uri->segment(3));
						$row = $query->result();
					?>
					<textarea class="form-control" name="note"
							  id="note"><?php echo $row[0]->order_notes ?></textarea>

					<!--<form id="subscribe-email-form" action="/notifications/subscribe/" method="post">-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalroom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Charge to Room</h4>
			</div>
			<form id="subscribe-email-form" action="<?php echo base_url() ?>main/submit"
				  method="post">
				<div class="modal-body">
					<?php
						$orderId = $this->uri->segment(3);
						$orderId = explode('-', $orderId);
						$orderId = implode(",", $orderId);
						$q = $this->db->query("
								select
								c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
								from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
								sum(sub_total_amount) total
								from pos_orders
								where id in(" . $orderId . ")) d
								where b.tax_id=c.id
								and b.order_id in(" . $orderId . ")
								group by c.name;"
						);
						$rows = $q->result();
						echo html('Total', $rows[0]->total);
						foreach ($rows as $row) {
							$l = '&nbsp&nbsp' . $row->name . '<small>&nbsp&nbsp&nbsp' . rupiah($row->tax_percent,
																							   2) . '% </small>';
							$v = $row->tax_amount;
							echo html($l, $v);
						}
						echo html('Grand Total', $rows[0]->grandtotal, 'grandtot');
					?>
					<hr/>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Customer</label>
								<select class="form-control" name="charge_to_room" id="charge_to_room">
									<option>- Choose -</option>
									<?php
										$dddd = $this->db->query("select * from v_in_house_guest");
										foreach ($dddd->result() as $row) {
											?>
											<script>
												window.Charge2Room = window.Charge2Room || {};
												Charge2Room[<?php echo $row->folio_id ?>] =
												<?php echo json_encode($row) ?>;
											</script>
											<option value="<?php echo $row->folio_id ?>">
												[<?php echo $row->room_type . ' / ' . $row->room_no?>]
												&nbsp;
												<?php echo $row->cust_firt_name . ' '.$row->cust_last_name
												?>
											</option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label>Check In</label>
							<span> : </span>
							<span id="check_in_date"></span>
						</div>
						<div class="col-lg-6">
							<label>Departure</label>
							<span> : </span>
							<span id="departure_date"></span>
						</div>
						<div class="col-lg-6">
							<label>Cash Bases</label>
							<span> : </span>
							<span id="is_cash_bases"></span>
						</div>
						<div class="col-lg-6">
							<label>Room Only</label>
							<span> : </span>
							<span id="is_room_only"></span>
						</div>
						<div class="col-lg-6">
							<label>Reservation Type</label>
							<span> : </span>
							<span id="reservation_type"></span>
						</div>
						<div class="col-lg-6">
							<label>Room No</label>
							<span> : </span>
							<span id="room_no"></span>
						</div>
						<div class="col-lg-6">
							<label>Room Rate</label>
							<span> : </span>
							<span id="room_rate_code"></span>
						</div>
						<div class="col-lg-6">
							<label>Room Type</label>
							<span> : </span>
							<span id="room_type"></span>
						</div>
						<div class="col-lg-6">
							<label>VIP Type</label>
							<span> : </span>
							<span id="vip_type"></span>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Notes</label>
								<textarea name="notes" style="width: 100%;height: 60px;"></textarea>
								<input type="hidden" class="form-control" name="folio_id">
								<input type="hidden" class="form-control" name="order_id"
									   value="<?php echo $orderId; ?>">
								<input type="hidden" class="form-control" name="grandtotal"
									   value="<?php echo $rows[0]->grandtotal; ?>">
								<input type="hidden" class="form-control" name="payment_type_id"
									   value="1">
								<input type="hidden" class="form-control" name="payment_amount"
									   value="<?php echo $rows[0]->grandtotal; ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close
					</button>
					<button id="submitChargeToRoom" type="submit" class="btn btn-primary"
							disabled>Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalGuestUse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Guest Use</h4>
			</div>
			<form id="subscribe-email-form" action="<?php echo base_url() ?>main/submit"
				  method="post">
				<div class="modal-body">
					<?php
						$orderId = $this->uri->segment(3);
						$orderId = explode('-', $orderId);
						$orderId = implode(",", $orderId);
						$q = $this->db->query("
								select
								c.name,b.tax_percent,sum(b.tax_amount) tax_amount,d.total, d.grandtotal
								from pos_order_taxes b,mst_pos_taxes c,(select sum(due_amount) grandtotal,
								sum(sub_total_amount) total
								from pos_orders
								where id in(" . $orderId . ")) d
								where b.tax_id=c.id
								and b.order_id in(" . $orderId . ")
								group by c.name;"
						);
						$rows = $q->result();
						echo html('Total', $rows[0]->total);
						foreach ($rows as $row) {
							$l = '&nbsp&nbsp' . $row->name . '<small>&nbsp&nbsp&nbsp' . rupiah($row->tax_percent,
																							   2) . '% </small>';
							$v = $row->tax_amount;
							echo html($l, $v);
						}
						echo html('Grand Total', $rows[0]->grandtotal, 'grandtot');
					?>
					<hr/>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Guest</label>
								<select class="form-control" name="guest_use" id="guest_use">
									<option>- Choose -</option>
									<?php
										$dddd = $this->db->query("select * from v_house_use_spent_monthly");
										foreach ($dddd->result() as $row) {
											?>
											<script>
												window.GuestUse = window.GuestUse || {};
												GuestUse[<?php echo $row->house_use_id ?>] =
												<?php echo json_encode($row) ?>;
											</script>
											<option value="<?php echo $row->house_use_id ?>">
												[<?php echo $row->period?>]
												&nbsp;
												<?php echo $row->cost_center . ' / ' .
													$row->house_use?>
											</option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label>Period</label>
							<span> : </span>
							<span id="period"></span>
						</div>
						<div class="col-lg-12">
							<label>House use</label>
							<span> : </span>
							<span id="house_use"></span>
						</div>
						<div class="col-lg-12">
							<label>Cost Center</label>
							<span> : </span>
							<span id="cost_center"></span>
						</div>
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-6">
									<label>Monthly Spent</label>
								</div>
								<div class="col-lg-6 pull-right">
									<span id="max_spent_monthly" class="pull-right"></span>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-6">
									<label>Current Transaction Amount</label>
								</div>
								<div class="col-lg-6 pull-right">
									<span id="current_transc_amount" class="pull-right"></span>
								</div>
							</div>
						</div>
					</div>
					<hr/>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Notes</label>
								<textarea name="notes" style="width: 100%;height: 60px;"></textarea>
								<input type="hidden" class="form-control" name="house_use_id">
								<input type="hidden" class="form-control" name="order_id"
									   value="<?php echo $orderId; ?>">
								<input type="hidden" class="form-control" name="grandtotal"
									   value="<?php echo $rows[0]->grandtotal; ?>">
								<input type="hidden" class="form-control" name="payment_type_id"
									   value="1">
								<input type="hidden" class="form-control" name="payment_amount"
									   value="<?php echo $rows[0]->grandtotal; ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close
					</button>
					<button id="submitGuestUse" type="submit" class="btn btn-primary"
							disabled>Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Menu List</h4>
			</div>
			<div class="modal-body">
				<!--<form id="subscribe-email-form" action="/notifications/subscribe/" method="post"> -->
				<table class="table table-striped">
					<tr>
						<th style="width: 10px">No</th>
						<th style="width: 180px">Menu</th>
						<th>Qty</th>
						<th>Amount</th>
						<th>#</th>
					</tr>

					<?php
						$tax = 0;
						$amount = 0;
						$service = 0;
						$query = $this->db->query("select b.id,b.name,sum(a.order_qty) qty,sum(price_amount) price
							from pos_orders_line_item a,inv_outlet_menus b
							where a.outlet_menu_id=b.id
							and a.order_id=" . $this->uri->segment(3) . "
							group by id");
						$i = 1;
						foreach ($query->result() as $row) {
							?>
							<tr>
								<td> <?php echo $i ?></td>
								<td> <?php echo $row->name ?></td>
								<td> <?php echo $row->qty ?></td>
								<td align="right">
									<?php echo number_format($row->price) ?>
								</td>

								<td align="center"><a
										href="<?php echo base_url() ?>main/void_item/<?php echo $this->uri->segment(3) ?>/<?php echo $row->id ?>/<?php echo $row->price ?>">
										<button type="button" class="btn btn-danger">Delete</button>
									</a></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td colspan="4" align="left">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>

				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!--<button type="submit" class="btn btn-primary">Save changes</button>-->
			</div>
			<!--     </form>-->
		</div>
	</div>
</div>

<!-- START CUSTOM TABS -->
<br/>
<div class="row">
	<div class="col-md-8">
		<!-- Custom Tabs -->
		<div class="nav-tabs-custom">

			<?php
				date_default_timezone_set('Asia/Jakarta');
			?>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<table>
						<tr>
							<th>Order Number/Table
							</th>
							<th>:</th>
							<th>
								<?php echo $this->global_model->get_no_bill($this->uri->segment(3)) ?>

							</th>
							<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th>Meal Time</th>
							<th>:</th>
							<th><?php echo $this->global_model->get_meal_time_name() ?></th>
						</tr>
						<tr>
							<th colspan="3">&nbsp;</th>
						</tr>
					</table>
					<!-- Main content -->
					<form action=" <?php echo base_url() ?>main/reload_pesan" name="myform"
						  id="myform" method="post">
						<section class="content">
							<!--
							<div class="input-group input-group-sm">
								<input type="text" name="cari" id="cari" class="form-control" placeholder="Search menu">
								<span class="input-group-btn">
									<button class="btn btn-info btn-flat" type="button">Go!</button>
								</span>
                            </div>
                            -->
							<div class="form-group row">
								<div class="col-xs-3">
									<!-- <label for="ex2">Menu Class</label>-->
									<select class="form-control" name="select_menu" id="select_menu"
											onchange="load()">
										<option value="">All</option>
										<?php
											$query = $this->db->query("select * from ref_outlet_menu_class");
											foreach ($query->result() as $row) {
												?>
												<option
													value="<?php echo $row->id ?>" <?php echo $row->id == $this->uri->segment(4) ? 'selected' : ''; ?>><?php echo $row->name ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="col-xs-3">
									<!-- <label for="ex2">Menu Class</label>-->
									<select class="form-control" name="select_sub_menu"
											id="select_sub_menu"
											onchange="load_sub()">
										<option value="">All</option>
										<?php
											if ($this->uri->segment(4) != "") {
												$query = $this->db->query("select b.id class_id, b.code class_code, b.name class_name, a.id, a.code, a.name, a.description
																		  from ref_outlet_menu_group a
																		  left join ref_outlet_menu_class b on a.menu_class_id = b.id or a.menu_class_id = b.parent_class_id
																		 where b.id = " . $this->uri->segment(4) . "
																		   and a.status = '1'");
												foreach ($query->result() as $row) {
													?>
													<option
														value="<?php echo $row->id ?>" <?php echo $row->id == $this->uri->segment(5) ? 'selected' : ''; ?>><?php echo $row->name ?></option>
													<?php
												}
											}
										?>
									</select>
								</div>
								<div class="col-xs-3">
									<input class="form-control" type="text" name="search_food"
										   placeholder="search menu..." id="search_food"
										   value="<?php echo $this->input->get('search') ?>"/>
								</div>
								<!-- 								<div class="col-xs-3">
                                                                    <button class="btn btn-info btn-flat" type="submit">Search</button>
                                                                </div> -->
								<!--<div class="col-xs-4">
                                    <label for="ex3">&nbsp;</label><br/>
                                    <button class="btn btn-info btn-flat" type="submit">Search !</button>
                                  </div>-->
							</div>
							<!-- /input-group -->
						</section>
					</form>
					<section class="content">

						<!-- Small boxes (Stat box) -->
						<div class="row">
							<?php
								//echo $this->input->post('select_menu');exit;
								// select a.* from mst_pos_tables a left join pos_orders b on a.id=b.table_id
								//if($this->input->post('select_menu')==""){
								$search = "";
								if ($this->input->get('search') != "") {
									$keyword = $this->input->get('search');
									$search = " and (a.name like '%$keyword%' or a.short_name like '%$keyword%')";
								}
								if ($this->uri->segment(5) != "") {
									$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . $search . " and a.menu_class_id ='" . $this->uri->segment(4) . "' and a.menu_group_id ='" . $this->uri->segment(5) . "'");
								} else if ($this->uri->segment(4) != "") {
									$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . $search . " and a.menu_class_id ='" . $this->uri->segment(4) . "'");
									//$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=".$this->session->userdata('outlet')." and meal_time_id=".$this->global_model->get_meal_time()." ");
								} else {
									$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . $search);
								}
								// echo $this->db->last_query();
								foreach ($query->result() as $row) {
									?>
									<div class="col-lg-3 col-xs-6">
										<div class="small-box bg-aqua">
											<a href="<?php echo base_url() ?>main/inputpesan/<?php echo $row->id ?>/<?php echo $row->menu_price ?>/<?php echo $row->menu_class_id ?>/<?php echo $this->uri->segment(3) . ($this->uri->segment(4) ? "/" . $this->uri->segment(4) : "") . ($this->uri->segment(5) ? "/" . $this->uri->segment(5) : "") . ($this->input->get('search') ? "?search=" . $this->input->get('search') : "") ?>"
											   class="small-box-footer">&nbsp;<center><img
														src="<?php echo base_url() ?>menu/<?php echo $row->image <> '' ? $row->image : 'no_image.svg'; ?>"
														width="130" height="80"></center>
												<center>  <?php echo $row->short_name ?></center>
												<?php echo number_format($row->menu_price) ?>
											</a>
										</div>

									</div>
									<?php
								}
							?>

						</div><!-- /.row -->

						<!-- top row -->
						<div class="row">
							<div class="col-xs-12 connectedSortable">

							</div><!-- /.col -->
						</div>
						<!-- /.row -->

						<!-- Main row -->
						<div class="row">
							<!-- Left col -->
							<section class="col-lg-6 connectedSortable">
								<!-- Box (with bar chart) -->

							</section><!-- /.Left col -->
							<!-- right col (We are only adding the ID to make the widgets sortable)-->

						</div><!-- /.row (main row) -->

					</section>
				</div><!-- /.tab-pane -->

			</div><!-- /.tab-content -->
		</div><!-- nav-tabs-custom -->
	</div><!-- /.col -->
	<div class="col-md-4">
		<div class="box box-solid">

			<div class="box-body">
				<div class="row">
					<div class="col-lg-12">
						<a class="btn btn-app" data-toggle="modal"
						   href="<?php echo base_url() ?>main">
							<i class="fa fa-home"></i>
							Home
						</a>
					</div>
					<div class="col-lg-12">
						<a class="btn btn-app"
						   data-toggle="modal" href="#myModalCash">
							<i class="fa fa-money"></i>
							Cash
						</a>
						<a class="btn btn-app" data-toggle="modal" href="#myModalcard" id="btn-cc">
							<i class="fa fa-credit-card"></i>
							Card
						</a>
						<a class="btn btn-app" data-toggle="modal" href="#myModalroom">
							<i class="fa fa-edit"></i>
							Charge room
						</a>
						<a class="btn btn-app" data-toggle="modal" href="#myModalGuestUse">
							<i class="fa fa-tags"></i>
							Guest use
						</a>
					</div>
					<!--<div class="col-lg-12">

						<a class="btn btn-app" data-toggle="modal"
						   href="<?php /*echo base_url() */ ?>main">
							<i class="fa fa-code"></i>
							Voucher
						</a>
						<a class="btn btn-app" data-toggle="modal"
						   href="<?php /*echo base_url() */ ?>main">
							<i class="fa fa-building-o"></i>
							City Ledger
						</a>
					</div>-->
					<hr>
					<div class="col-lg-12">
						<a class="btn btn-app" data-toggle="modal" href="#myModalMerge">
							<i class="fa fa-arrow-down" aria-hidden="true"></i>
							Merge
						</a>
						<a class="btn btn-app" data-toggle="modal" href="">
							<i class="fa fa-arrows-alt"></i>
							Split
						</a>
						<a class="btn btn-app" data-toggle="modal" href="#myModalNoPost">
							<i class="fa fa-times-circle"></i>
							No Post
						</a>
						<a class="btn btn-app btn-warning" data-toggle="modal"
						   href="<?php echo base_url() ?>main/open_cash/<?php echo $this->uri->segment(3) ?>">
							<i class="fa fa-th-large"></i>
							Cash draw
						</a>
					</div>
					<div class="col-lg-12 text-center" style="margin-left: -5px;">
						<iframe width="100%" scrolling="yes" frameBorder="0" height="375"
								src="<?php echo base_url() ?>main/get_total/<?php echo $this->uri->segment(3) ?>"
								allowfullscreen></iframe>
					</div>
					<div class="col-lg-12">
						<a class="btn btn-app bg-red" data-toggle="modal" href="#myModal">
							<i class="fa fa-times"></i>
							Void Menu
						</a>
						<a class="btn btn-app bg-yellow"
						   href="<?php echo base_url() ?>main/print_kitchen/<?php echo $this->uri->segment(3) ?>">
							<i class="fa fa-print"></i>
							Print Order
						</a>
						<a class="btn btn-app bg-green">
							<i class="fa fa-edit" data-toggle="modal" href="#myModalOpenMenu"></i>
							Open Menu
						</a>
						<a class="btn btn-app bg-yellow" data-toggle="modal" href="#myModalnote">
							<i class="fa fa-edit"></i>
							Note
						</a>
					</div>
				</div>
			</div>

		</div><!-- /.box -->
	</div><!-- /.col (left) -->
</div><!-- /.row -->

<script type="text/javascript">
	var delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();
	var trim = function (str) {
		return str.replace(/\t\t+|\n\n+|\s\s+/g, ' ').trim()
	};
	var swipeCard = function (str) {
		return trim(str)
		.replace(/\/\s\^|\s\^/g, '/^')
		.split(/\;|\%B|\^|\/\^|\?\;|\=|\?/g)
		.slice(1, -1)
	};
	// $('#search_food').keyboard({layout: 'qwerty',autoAccept: true,enterNavigation:true});
	$.keyboard.keyaction.enter = function (base) {
		// console.log(base);
		if (base.el.id === "search_food") {
			base.accept();      // accept the content
			$('form').submit(); // submit form on enter
			// } else {
			//   base.insertText('\r\n'); // textarea
		}
	};

	// autoAccept option to true and leave the enterNavigation option set as true
	function print(order) {
		//alert('sss');
		//__action=print
		PopupCenter('<?php echo REPORT_BIRT?>struk_kitchen.rptdesign&__format=pdf&no_bill=<?php echo $this->session->userdata('no_bill')?>&table=<?php echo $this->session->userdata('table')?>&date=<?php echo date('Y-M-d')?>&outlet=<?php echo $this->session->userdata('outlet')?>&waitress=<?php echo $this->session->userdata('name')?>', 'xtf', '600', '600');
		myWindow.document.close();
		myWindow.focus();
		myWindow.print();
		myWindow.close();
	}

	function print_test() {
		PopupCenter('<?php echo base_url()?>main/print_kitchen', 'xtf', '600', '600');
		myWindow.document.close();
		myWindow.focus();
		myWindow.print();
		myWindow.close();
	}

	function print_payment(order) {

		$.ajax({
				url: '<?php echo base_url()?>main/payment_update/<?php echo $this->global_model->get_no_bill($this->uri->segment(3))?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->session->userdata('outlet')?>',
			}
		);

		PopupCenter(' <?php echo REPORT_BIRT?>struk_order.rptdesign&__format=pdf&no_bill=<?php echo $this->global_model->get_no_bill($this->uri->segment(3))?>&table=<?php echo $this->uri->segment(3)?>&date=<?php echo date('Y-M-d')?>&outlet=<?php echo $this->session->userdata('outlet')?>&waitress=<?php echo $this->session->userdata('name')?>', 'xtf', '600', '600');
		myWindow.document.close();
		myWindow.focus();
		myWindow.print();
		myWindow.close();
	}

	function PopupCenter(url, title, w, h) {
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 2) - (h / 2)) + dualScreenTop;
		var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

		// Puts focus on the newWindow
		if (window.focus) {
			newWindow.focus();
		}
	}

	function cancel() {
		alert('Cancel This Order?');
	}

	function load() {
		$("#select_sub_menu").val("");
		document.forms["myform"].submit();
		//var id=document.getElementById('select_menu').value;
		// alert(id);
	}

	function load_sub() {
		document.forms["myform"].submit();
		//var id=document.getElementById('select_menu').value;
		// alert(id);
	}

	function menu_select() {
		document.forms["myform"].submit();
	}
	//
	$(document).ready(function () {
		$('#cc_type').on('change', function () {
			var v = $(this).val();
			if (v == 'debit') {
				$('#card_name').parent().hide();
				$('#card_name').val('');
			} else {
				$('#card_name').parent().show();
			}
		});
		$("#card_swiper").keydown(function (e) {
			if (e.keyCode == 13) {
				e.preventDefault();
			}
			return
		});
		$('#card_swiper').on('keyup', function () {
			var el = $(this);
			delay(function () {
				var arr = swipeCard(el.val());
				$('#card_name').val('');
				if (arr.length) {
					$('#card_no').val(arr[0]);
					if ($('#cc_type').val() == 'credit') {
						$('#card_name').val(arr[1]);
					} else {
					}
				}
				el.val('');
			}, 1000);
		});
		$('input[type="text"]').keyboard({layout: 'qwerty'});
		$('textarea').keyboard({layout: 'qwerty'});
		$('input[type="currency"]').on('blur', function () {
			var bayar = parseFloat($(this).data('value'));
			var grandtotal = parseFloat($('label[for="grandtot"]').attr('val'));
			var change = bayar - grandtotal;
			var displayChange = rupiahJS(change);
			$('label[for="change"]').html(displayChange);
		});
		$("#search_food").keypress(function (e) {
			if (e.which == 13) {
				document.forms["myform"].submit();
				return false;    //<---- Add this line
			}
		});
		//
		var rupiahJS = function (val) {
			return parseFloat(val.toString().replace(/\,/g, "")).toFixed(2)
			.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		};
		var showCharge2RoomInfo = function (i) {
			var d = Charge2Room[i];
			$('#submitChargeToRoom').attr('disabled', 1);
			if (d) {
				$('input[name="folio_id"]').val(d.folio_id);
				$('#check_in_date').html(d.check_in_date);
				$('#departure_date').html(d.departure_date);
				$('#is_cash_bases').html(d.is_cash_bases);
				$('#is_room_only').html(d.is_room_only);
				$('#reservation_type').html(d.reservation_type);
				$('#room_no').html(d.room_no);
				$('#room_rate_code').html(d.room_rate_code + '/' + d.room_rate_name);
				$('#room_type').html(d.room_type + '/' + d.room_type_name);
				$('#vip_type').html(d.vip_type);
				if (d.is_cash_bases.toLowerCase() === 'n') {
					$('#submitChargeToRoom').removeAttr('disabled');
				}
			}
		};
		var showGuestUseInfo = function (i) {
			var d = GuestUse[i];
			$('#submitGuestUse').attr('disabled', 1);
			if (d) {
				$('input[name="house_use_id"]').val(d.house_use_id);
				$('#cost_center').html(d.cost_center);
				$('#house_use').html(d.house_use);
				$('#current_transc_amount').html(rupiahJS(d.current_transc_amount));
				$('#max_spent_monthly').html(rupiahJS(d.max_spent_monthly));
				$('#period').html(d.period);
				var balance = parseFloat(d.max_spent_monthly) - parseFloat(d.current_transc_amount);
				var grandtotal = $('#period').closest('.modal-body').find
				('label[for="grandtot"]').attr('val');
				if (balance > 0) {
				    if (balance - parseFloat(grandtotal) >= 0) {
						$('#submitGuestUse').removeAttr('disabled');
					}
				}
			}
		};
		$('#charge_to_room').on('change', function () {
			Charge2Room.current = $(this).val();
			showCharge2RoomInfo(Charge2Room.current);
		});
		$('#guest_use').on('change', function () {
			GuestUse.current = $(this).val();
			showGuestUseInfo(GuestUse.current);
		});

		$('.list-group.checked-list-box .list-group-item').each(function () {
			// Settings
			var $widget = $(this),
				$checkbox = $('<input type="checkbox" class="hidden" />'),
				color = ($widget.data('color') ? $widget.data('color') : "primary"),
				style = (
				    !$widget.data('style') || $widget.data('style') == "button" ? "btn-" : "list-group-item-"
				),
				settings = {
					on: {
						icon: 'glyphicon glyphicon-check'
					},
					off: {
						icon: 'glyphicon glyphicon-unchecked'
					}
				};

			console.log($widget.data('style'));
			$widget.css('cursor', 'pointer')
			$widget.append($checkbox);

			// Event Handlers
			$widget.on('click', function () {
				$checkbox.prop('checked', !$checkbox.is(':checked'));
				$checkbox.triggerHandler('change');
				updateDisplay();
			});
			$checkbox.on('change', function () {
				updateDisplay();
			});


			// Actions
			function updateDisplay() {
				var isChecked = $checkbox.is(':checked');

				// Set the button's state
				$widget.data('state', (isChecked) ? "on" : "off");

				// Set the button's icon
				$widget.find('.state-icon')
				.removeClass()
				.addClass('state-icon ' + settings[$widget.data('state')].icon);

				// Update the button's color
				if (isChecked) {
					$widget.addClass(style + color + ' active');
				} else {
					$widget.removeClass(style + color + ' active');
				}
			}

			// Initialization
			function init() {

				if ($widget.data('checked') == true) {
					$checkbox.prop('checked', !$checkbox.is(':checked'));
				}

				updateDisplay();

				// Inject the icon if applicable
				if ($widget.find('.state-icon').length == 0) {
					$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
				}
			}
			init();
		});
		$('label[for="grantotal-merge-me"]').css('margin-right', '20px');
		var nextLocation;
		var paths = [window.location.href];
		$("#check-list-box li").on('click', function(event){
			event.preventDefault();
			var me = parseFloat($('label[for="grantotal-merge-me"]').attr('val'));
			var oth = 0;
			var paths_ = [];
			$("#check-list-box li.active").each(function(idx, li) {
			    oth += parseFloat(mergeWith[li.id].due_amount);
			    paths_.push(mergeWith[li.id].order_id)
			});
			$('#grantotal-merge').html(rupiahJS(me + oth));
			if (oth > 0) {
				nextLocation = paths.concat(paths_).join('-');
				$('#mergeTable').attr("action", nextLocation);
				$('#mergeTableBtn').removeAttr('disabled')
			} else {
				nextLocation = "";
				$('#mergeTable').attr("action", "#");
				$('#mergeTableBtn').attr('disabled', 1);
			}
		});
	});
</script>
