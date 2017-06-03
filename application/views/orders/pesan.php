<!-- Modal -->
<div class="modal fade" id="myModalcard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Credit Card Payment</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form" action="#" method="post">
					<div class="form-group">
						<div class="form-group">
							<label>Payment Method</label>
							<select class="form-control">
								<?php
									$query = $this->db->query("select * from ref_payment_method");
									foreach ($query->result() as $row) {
										?>
										<option value="<?= $row->code ?>"><?= $row->name ?>
											-<?= $row->description ?></option>
										<?
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="usr">Card Number:</label>
							<input type="text" class="form-control" id="card_no" name="card_no">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"
						onclick="print_payment(<?= $this->session->order_no; ?>)">Submit
				</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- modal -->
<div class="modal fade" id="myModalOpenMenu" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Open Menu</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form" action="<?php echo base_url() ?>main/openmenu"
					  method="post">
					<input type="hidden" name="bill"
						   value="<?php echo $this->global_model->get_no_bill($this->uri->segment(3)) ?>">
					<input type="hidden" name="outlet"
						   value="<?php echo $this->session->userdata('outlet') ?>">
					<input type="hidden" name="table" value="<?php echo $this->uri->segment(3) ?>">

					<table class="table table-striped">
						<thead>
						<tr>
							<th>Menu Name</th>
							<th>Qty</th>
							<th>Price</th>
						</tr>
						</thead>
						<tbody>
						<?php
							for ($i = 1; $i <= 3; $i++) {
								?>
								<tr>
									<td><input type="text" class="form-control"
											   id="menu_<?php echo $i ?>"
											   name="menu_<?php echo $i ?>">
									</td>
									<td><input type="text" class="form-control"
											   id="qty_<?php echo $i ?>"
											   name="qty_<?php echo $i ?>">
									</td>
									<td><input type="text" class="form-control"
											   id="price_<?php echo $i ?>"
											   name="price_<?php echo $i ?>"></td>
								</tr>
								<?php
							}
						?>
						</tbody>
					</table>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close
						</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- modal -->
<div class="modal fade" id="myModalnote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Menu List</h4>
			</div>
			<div class="modal-body">
				<!--<form id="subscribe-email-form" action="/notifications/subscribe/" method="post"> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!--<button type="submit" class="btn btn-primary">Save changes</button>-->
			</div>
			<!-- </form>-->
		</div>
	</div>
</div>

<!-- modal room -->
<div class="modal fade" id="myModalroom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Include Room</h4>
			</div>
			<div class="modal-body">
				<form id="subscribe-email-form" action="<?php echo base_url() ?>main/include_room"
					  method="post">
					<div class="col-xs-8">
						<input type="hidden" name="bill"
							   value="<?php echo $this->global_model->get_no_bill($this->uri->segment(3)) ?>">
						<input type="hidden" name="outlet"
							   value="<?php echo $this->session->userdata('outlet') ?>">
						<input type="hidden" name="table"
							   value="<?php echo $this->uri->segment(3) ?>">
						<select class="form-control" name="room" id="room">
							<?php
								foreach ($this->global_model->guest()->result() as $row) {
									?>
									<option value="<?php echo $row->folio_id ?>">
										[<?php echo $row->folio_id ?>
										]&nbsp;<?php echo $row->guest_name ?></option>
									<?php
								}
							?>
						</select>
					</div>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close
					</button>
					<button type="submit" class="btn btn-primary">Save changes</button>

				</form>
			</div>
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
						$query = $this->db->query("select menu_id,sum(amount) as amount,order_no,sum(qty) as qty,tax,service from pos_outlet_order_detil 
                                        where is_void=0 and table_id=" . $this->uri->segment(3) . " and outlet_id=" . $this->session->userdata('outlet') . " group by menu_id ");
						$i = 1;
						foreach ($query->result() as $row) {
							?>
							<tr>
								<td> <?php echo $i ?></td>
								<td> <?php echo $this->global_model->get_menu_name($row->menu_id) ?></td>
								<td> <?php echo $row->qty ?></td>
								<td align="right">
									<?php echo number_format($row->amount) ?>
								</td>

								<td align="center"><a
										href="<?php echo base_url() ?>main/void_item/<?php echo $this->session->userdata('table') ?>/<?php echo $this->session->userdata('outlet') ?>/<?php echo $row->menu_id ?>">
										<button type="button" class="btn btn-danger">Delete</button>
									</a></td>
							</tr>
							<?php
							$tax += $row->tax;
							$amount += $row->amount;
							$service += $row->service;
							$i++;
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
			<!-- <ul class="nav nav-tabs">
                                 <li class="active"><a href="#tab_1" data-toggle="tab"><? //=$row->name?></a></li>
                                <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                                </ul>-->
			<!--<a class="btn btn-app" href=' <?php echo base_url('main') ?>'>
                                        <i class="fa fa-home"></i> Home
                                    </a>-->
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
								/<?php echo $this->uri->segment(3) ?>
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
							<!--<div class="input-group input-group-sm">
                                          <input type="text" name="cari" id="cari" class="form-control" placeholder="Search menu">
                                                          <span class="input-group-btn">
                                                              <button class="btn btn-info btn-flat" type="button">Go!</button>
                                                          </span>
                            </div>-->

							<div class="form-group row">
								<div class="col-xs-3">
									<!--<label for="ex2">Menu Class</label>-->
									<select class="form-control" name="select_menu">
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
								<!--
                                <div class="col-xs-4">
                                    <label for="ex3">&nbsp;</label><br/>
                                    <button class="btn btn-info btn-flat" type="submit">Go!</button>
                                  </div>
                                -->
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
								//$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=".$this->session->userdata('outlet')." and a.menu_class_id ='".$this->input->post('select_menu')."'");
								//if($this->input->post('select_menu')==""){
								if ($this->uri->segment(4) == "") {
									// $query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=".$this->session->userdata('outlet')." and meal_time_id=".$this->global_model->get_meal_time()." ");
									$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . "  ");
								} else {
									$query = $this->db->query("select a.* from inv_outlet_menus a where a.outlet_id=" . $this->session->userdata('outlet') . " and a.menu_class_id ='" . $this->uri->segment(4) . "'");
								}
								//  echo $this->db->last_query();
								foreach ($query->result() as $row) {
									?>
									<div class="col-lg-3 col-xs-6">
										<div class="small-box bg-aqua">
											<a href="<?php echo base_url() ?>main/inputpesan/<?php echo $row->id ?>/<?php echo $row->menu_price ?>/<?php echo $row->menu_class_id ?>/<?php echo $this->uri->segment(3) ?>"
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
			<div class="box-header">

				<!-- <a class="btn btn-app" href=' <?php echo base_url('main') ?>'>
                                        <i class="fa fa-home"></i> Home
                                    </a>-->

				<a class="btn btn-app" data-toggle="modal" href="<?php echo base_url() ?>main">
					<i class="fa fa-home"></i> Home
				</a>

				<a class="btn btn-app" data-toggle="modal" href="#myModalroom">
					<i class="fa fa-edit"></i> Room
				</a>

				<a class="btn btn-app" data-toggle="modal" href="#myModalcard">
					<i class="fa fa-credit-card"></i> Card
				</a>

				<a class="btn btn-app ">
					<i class="fa fa-money"
					   onclick="print_payment( <?php echo $this->session->order_no; ?>)"></i> Cash
				</a>

			</div><!-- /.box-header -->
			<div class="box-body text-center">

				<iframe width="360" scrolling="yes" frameBorder="0" height="415"
						src="<?php echo base_url() ?>main/get_total/<?php echo $this->uri->segment(3) ?>"
						allowfullscreen></iframe>
				<!-- end -->

				<!-- <a class="btn btn-app bg-red" href='<? //=base_url()?>main/cancel_order' onclick="javasciprt: return confirm('Are you Sure Cancel This Order ?')">
                                        <i class="fa fa-times"></i>Cancel Order</a>
                                    </a>-->

				<a class="btn btn-app bg-red" data-toggle="modal" href="#myModal">
					<i class="fa fa-times"></i>Cancel Order</a>
				</a>

				<a class="btn btn-app bg-yellow"
				   onclick="print( <?php echo $this->session->order_no; ?>)">
					<i class="fa fa-print"></i>Print Order</a>
				</a>

				<a class="btn btn-app bg-green">
					<i class="fa fa-edit" data-toggle="modal" href="#myModalOpenMenu"></i> Open Menu
				</a>
				<a class="btn btn-app bg-yellow" data-toggle="modal" href="#myModalnote">
					<i class="fa fa-edit"></i> Note
				</a>

			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col (left) -->

</div><!-- /.row -->

<script type="text/javascript">

	function print(order) {
		//__action=print
		PopupCenter(' <?php echo REPORT_BIRT?>struk_kitchen.rptdesign&__format=pdf&no_bill= <?php echo $this->session->userdata('no_bill')?>&table= <?php echo $this->session->userdata('table')?>&date= <?php echo date('Y-M-d')?>&outlet= <?php echo $this->session->userdata('outlet')?>&waitress= <?php echo $this->session->userdata('name')?>', 'xtf', '600', '600');
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
				url: '<?php echo base_url()?>main/payment_update/<?php echo $this->session->userdata('no_bill')?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->session->userdata('outlet')?>',
			}
		);

		PopupCenter(' <?php echo REPORT_BIRT?>struk_order.rptdesign&__format=pdf&no_bill=<?php echo $this->session->userdata('no_bill')?>&table=<?php echo $this->uri->segment(3)?>&date= <?php echo date('Y-M-d')?>&outlet= <?php echo $this->session->userdata('outlet')?>&waitress= <?php echo $this->session->userdata('name')?>', 'xtf', '600', '600');
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

	function menu_select() {
		document.forms["myform"].submit();

	}
</script>
